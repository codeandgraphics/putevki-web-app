import $ from 'jquery';
import Bloodhound from 'typeahead.js';

import { IS_DEV } from '../../app';
import Humanize from '../utils/humanize';

export default class SearchForm {

  constructor() {
    this.$ = {
      form: $('#searchForm'),
    };

    this.$.nights = this.$.form.find('.popup-nights');
    this.$.people = this.$.form.find('.popup-people');
    this.$.from = this.$.form.find('.from');
    this.$.popup = $('.popup');

    this.popularCountries = this.$.form.data('countries').split(',');
    this.popularRegions = this.$.form.data('regions').split(',');

    this.endpoint = '/ajax/';

    this.limits = {
      nights: {
        min: 3,
        max: 29,
      },
      people: {
        min: 1,
        max: 5,
        kids: 3,
      },
    };

    this.data = {};

    this.initData();

    this.bindActions();
  }

  initData() {
    this.data.departure = this.$.form.data('departure');

    this.data.country = this.$.form.data('country');
    this.data.region = this.$.form.data('region');
    this.data.hotel = this.$.form.data('hotel');

    this.data.date = this.$.form.data('date');
    this.data.date_range = this.$.form.data('date-range');

    this.data.nights = this.$.form.data('nights');
    this.data.nights_range = this.$.form.data('nights-range');

    this.data.adults = this.$.form.data('adults');
    this.data.kids = (typeof this.$.form.data('kids') === 'number') ?
      [this.$.form.data('kids')] :
      $.map(this.$.form.data('kids').split('+'), value => parseInt(value, 10));

    if (this.data.kids[0] === 0) {
      this.data.kids = [];
    }

    this.data.stars = this.$.form.data('stars');
    this.data.meal = this.$.form.data('meal');

    this.data.hotel = this.$.form.data('hotel');

    this.data.operator = this.$.form.data('operator');
  }

  bindActions() {
    this.fromActions();
    this.whereActions();
    this.nightsActions();
    this.peopleActions();

    /* this.dateActions();

    this.submitActions();

    $(document).mouseup((e) => {
      const container = this.$.popup;

      if (!container.is(e.target)
        && container.has(e.target).length === 0) {
        container.addClass('hidden');
      }
    }); */
  }

  fromActions() {
    const $fromText = this.$.from.find('.from-text');

    this.$.from.find('select').on('change', (e) => {
      const $el = $(e.target);
      const id = parseInt($el.val(), 10);
      const gen = $el.find(':selected').attr('data-gen');

      const isVisible = (id !== 99);
      $fromText.toggle(isVisible);

      this.setValue('departure', id);
      this.$.from.find('#fromDropdown span').text(gen);

      if (this.$.from.hasClass('search')) {
        this.$.form.find('.search-button button').click();
      }
    });
  }

  whereActions() {
    const $where = this.$.form.find('.where');
    const $whereInput = $where.find('input');
    const $close = $('<a href="#" class="close"><i class="ion-ios-close-empty"></i></a>');

    $where.append($close);

    $close.on('click', () => {
      $whereInput.typeahead('val', '').focus();
      this.setValue('country', false);
      this.setValue('region', false);
      $close.hide();
      return false;
    });

    $.getJSON(`${this.endpoint}destinations/`, (data) => {
      const countriesList = [];
      const regionsList = [];

      data.countries.forEach((country) => {
        countriesList[country.id] = country;
        countriesList[country.id].isCountry = true;
      });

      data.regions.forEach((region) => {
        regionsList[region.id] = region;
        regionsList[region.id].isRegion = true;
      });

      const countries = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify(obj) { return obj.name; },
        local: data.countries,
      });

      const regions = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name', 'country_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify(obj) { return obj.name; },
        local: data.regions,
      });

      const hotels = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: `${self.endpoint}hotels/?query=%QUERY`,
          wildcard: '%QUERY',
          filter(items) {
            return $.map(items, item => ({
              id: item.id,
              name: item.name,
              country: item.country,
              region: item.region,
              regionName: item.regionName,
              isHotel: true,
            }));
          },
        },
      });

      const countriesDefault = (q, sync) => {
        if (q === '') {
          sync(countries.get(this.popularCountries));
        } else {
          countries.search(q, sync);
        }
      };

      const regionsDefault = (q, sync) => {
        if (q === '') {
          sync(regions.get(this.popularRegions));
        } else {
          regions.search(q, sync);
        }
      };

      $whereInput.typeahead({
        hint: true,
        highlight: true,
        minLength: 0,
      }, {
        name: 'countries',
        source: countriesDefault,
        display: 'name',
        displayKey: 'id',
        templates: {
          header: '<h3>Страны</h3>',
          suggestion(item) {
            return `<div><span>${item.name}</span></div>`;
          },
        },
      }, {
        name: 'regions',
        source: regionsDefault,
        display: 'name',
        displayKey: 'id',
        limit: 7,
        templates: {
          header: '<h3>Регионы</h3><div class="suggestions">',
          footer: '</div>',
          suggestion(item) {
            return `<div><span>${item.name}</span> <i> ${countriesList[item.country]}</i></div>`;
          },
        },
      }, {
        name: 'hotels',
        source: hotels,
        display: 'name',
        templates: {
          header: '<h3>Отели</h3><div class="suggestions">',
          footer: '</div>',
          suggestion(item) {
            return `<div><span>${item.name.toLowerCase()}</span><i> ${item.regionName}</i></div>`;
          },
        },
      }).on('typeahead:autocomplete typeahead:select', (e, object) => {
        let hotelId = false;
        let regionId = false;
        let countryId = false;

        let where = false;

        if (object.isCountry) {
          countryId = object.id;
          where = object.name;
        }

        if (object.isRegion) {
          regionId = object.id;
          countryId = object.country;
          where = `${countriesList[countryId]}(${regionsList[regionId].name})`;
        }

        if (object.isHotel) {
          hotelId = object.id;
          regionId = object.region;
          countryId = object.country;
          where = `${countriesList[countryId]}(${regionsList[regionId].name})`;
        }

        $where.removeClass('error');
        this.setValue('country', countryId);
        this.setValue('region', regionId);
        this.setValue('hotel', hotelId);
        this.setValue('where', where);
        $close.show();

        return false;
      }).on('typeahead:change', (e, value) => {
        $where.removeClass('error');

        if (
          countries.get(value).length === 0 &&
          regions.get(value).length === 0 &&
          !this.getValue('hotel')
        ) {
          this.setValue('country', false);
          this.setValue('region', false);
          this.setValue('hotel', false);
          this.setValue('where', false);
          $whereInput.typeahead('val', '');
          $close.hide();
        }
      });

      const country = this.getValue('country');
      const region = this.getValue('region');
      let where = countriesList[country];

      if (regionsList[region]) {
        where += `(${regionsList[region].name})`;
        $whereInput.typeahead('val', regionsList[region].name);
        $close.show();
      } else if (country) {
        $whereInput.typeahead('val', countriesList[country]);
        $close.show();
      }

      this.setValue('where', where);

      this.formReady();
    });
  }

  nightsActions() {
    const limits = this.limits.nights;
    let nights = this.getValue('nights');
    const range = this.getValue('nights_range');

    const $popup = this.$.nights.find('.popup');
    const $selector = $popup.find('.selector');
    const $range = $popup.find('.range-checkbox input');

    this.$.nights.find('.range').toggle(range);
    $range.prop('checked', range);
    this.setText('nights', nights);

    if (nights >= limits.max) {
      $selector.find('.plus').addClass('disabled');
    } else {
      $selector.find('.plus').removeClass('disabled');
    }

    if (nights <= limits.min) {
      $selector.find('.minus').addClass('disabled');
    } else {
      $selector.find('.minus').removeClass('disabled');
    }

    this.$.nights.find('.value, .range').click(() => {
      $popup.removeClass('hidden');
      $selector.find('.minus').off('click').on('click', (e) => {
        const $el = $(e.target);
        if (!$el.hasClass('disabled')) {
          nights -= 1;
          this.setText('nights', nights);
          this.setValue('nights', nights);
        }
        if (nights <= limits.min) {
          $el.addClass('disabled');
        } else {
          $el.removeClass('disabled');
        }
        if (nights < limits.max) $selector.find('.plus').removeClass('disabled');
        return false;
      });

      $selector.find('.plus').off('click').on('click', (e) => {
        const $el = $(e.target);
        if (!$el.hasClass('disabled')) {
          nights += 1;
          this.setText('nights', nights);
          this.setValue('nights', nights);
        }
        if (nights >= limits.max) {
          $el.addClass('disabled');
        } else {
          $el.removeClass('disabled');
        }
        if (nights > limits.min) $selector.find('.minus').removeClass('disabled');
        return false;
      });

      $range.off('change').on('change', () => {
        if ($range.is(':checked')) {
          this.setValue('nights_range', true);
          this.$.nights.find('.range').show();
        } else {
          this.setValue('nights_range', false);
          this.$.nights.find('.range').hide();
        }
      });

      return false;
    });
  }

  peopleActions() {
    const limits = this.limits.people;
    let adults = this.getValue('adults');
    const kids = this.getValue('kids');
    let people = adults;
    if (kids.length) people += kids.length;

    const $popup = this.$.people.find('.popup');
    const $adultsSelector = $popup.find('.selector');
    const $kidsSelect = $popup.find('select');
    const $kidsAlert = $popup.find('.info');
    const $kidTemplate = $popup.find('.kid.template');

    const kidDelete = (e) => {
      const $el = $(e.target);

      kids.splice($.inArray(parseInt($el.parent().data('age'), 10), kids), 1);
      people -= 1;
      this.setValue('kids', kids);
      this.setText('people', people);
      $el.parent().remove();

      if (kids.length >= limits.kids) {
        $kidsSelect.hide();
        $kidsAlert.show();
      } else {
        $kidsSelect.show();
        $kidsAlert.hide();
      }
      console.log(kids);
      return false;
    };

    this.setText('adults', adults);
    this.setText('people', people);

    for (let i = 0; i < kids.length; i += 1) {
      const $kid = this.createKid($kidTemplate, Humanize('age', kids[i]), kids[i], kidDelete);

      this.$.people.find('.kids').append($kid);
    }

    if (adults >= limits.max) {
      $adultsSelector.find('.plus').addClass('disabled');
    } else {
      $adultsSelector.find('.plus').removeClass('disabled');
    }

    if (adults <= limits.min) {
      $adultsSelector.find('.minus').addClass('disabled');
    } else {
      $adultsSelector.find('.minus').removeClass('disabled');
    }

    if (kids.length >= limits.kids) {
      $kidsSelect.hide();
      $kidsAlert.show();
    } else {
      $kidsSelect.show();
      $kidsAlert.hide();
    }

    this.$.people.find('.value').click(() => {
      $popup.removeClass('hidden');

      $adultsSelector.find('.minus').off('click').on('click', (e) => {
        const $el = $(e.target);

        if (!$el.hasClass('disabled')) {
          adults -= 1;
          people -= 1;

          this.setValue('adults', adults);
          this.setText('adults', adults);
          this.setText('people', people);
        }

        if (adults <= limits.min) {
          $el.addClass('disabled');
        } else {
          $el.removeClass('disabled');
        }

        if (adults < limits.max) {
          $adultsSelector.find('.plus').removeClass('disabled');
        }

        return false;
      });

      $adultsSelector.find('.plus').off('click').on('click', (e) => {
        const $el = $(e.target);

        if (!$el.hasClass('disabled')) {
          adults += 1;
          people += 1;

          this.setValue('adults', adults);
          this.setText('adults', adults);
          this.setText('people', people);
        }

        if (adults >= limits.max) {
          $el.addClass('disabled');
        } else {
          $el.removeClass('disabled');
        }

        if (adults > limits.min) {
          $adultsSelector.find('.minus').removeClass('disabled');
        }


        return false;
      });

      $kidsSelect.off('change').on('change', (e) => {
        const $el = $(e.target);
        const age = parseInt($el.val(), 10);

        const $kid = this.createKid($kidTemplate, $kidsSelect.find('option:selected').text(), age, kidDelete);

        this.$.people.find('.kids').append($kid);

        kids.push(age);
        people += 1;

        this.setValue('kids', kids);
        this.setText('people', people);

        if (kids.length >= limits.kids) {
          $kidsSelect.hide();
          $kidsAlert.show();
        } else {
          $kidsSelect.show();
          $kidsAlert.hide();
        }

        $el.val('');
        return false;
      });

      return false;
    });
  }

  setValue(key, value) {
    this.data[key] = value;
  }

  getValue(key) {
    let value = this.data[key];

    if (value === 'true') {
      value = true;
    } else if (value === 'false') {
      value = false;
    }

    if (key === 'date_range' || key === 'nights_range') {
      value = (value === 1);
    }

    if (key === 'kids') {
      if (value === 0) {
        value = [];
      } else if (typeof value === 'string') {
        value = value.split(',');
      }
    }

    if (key === 'adults') {
      value = parseInt(value, 10);
    }

    return value;
  }

  setText(type, value) {
    if (type === 'nights') {
      const nightsText = Humanize.nights(value);
      this.$.nights.find('.value').text(nightsText);
      this.$.nights.find('.popup .selector .param').text(nightsText);
    }

    if (type === 'adults') {
      const adultsText = Humanize.adults(value);
      this.$.people.find('.popup .selector .param').text(adultsText);
    }

    if (type === 'people') {
      const peopleText = Humanize.people(value);
      this.$.people.find('.value').text(peopleText);
    }
  }

  formReady() {
    this.$.form.find('.loader').hide();

    if (IS_DEV) console.log('[ФОРМА ПОИСКА] Форма загружена', new Date());
  }
}
