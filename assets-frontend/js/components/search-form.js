import $ from 'jquery';
import Bloodhound from 'typeahead.js';
import moment from 'moment';
import 'air-datepicker';

import { IS_DEV, DATE_FORMAT, DATE_VISIBLE_FORMAT } from '../../app';
import * as Humanize from '../utils/humanize';

const DATE_RANGE = 2;
const NIGHTS_RANGE = 2;

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
    this.data.from = this.$.form.data('from');
    this.data.where = this.$.form.data('where');
    this.data.when = this.$.form.data('when');
    this.data.people = this.$.form.data('people');
    this.data.filters = this.$.form.data('filters');
    this.data.operator = this.$.form.data('operator');

    this.range = this.data.when.dateFrom === this.data.when.dateTo ? DATE_RANGE : 0;
  }

  bindActions() {
    this.fromActions();
    this.whereActions();
    this.whenActions();
    this.nightsActions();
    this.peopleActions();


    this.submitActions();

    $(document).mouseup((e) => {
      const container = this.$.popup;

      if (!container.is(e.target)
        && container.has(e.target).length === 0) {
        if (container.hasClass('active')) {
          container.removeClass('active');
        }
      }
    });
  }

  /** Actions */

  fromActions() {
    const $fromText = this.$.from.find('.from-text');

    this.$.from.find('select')
      .on('change', (e) => {
        const $el = $(e.target);
        const id = parseInt($el.val(), 10);
        const gen = $el.find(':selected').attr('data-gen');

        const isVisible = (id !== 99);
        $fromText.toggle(isVisible);

        this.data.from = id;
        this.$.from.find('#fromDropdown span').text(gen);

        if (this.$.from.hasClass('search')) {
          this.$.form.find('.search-button button').click();
        }
      })
      .find(`option[value=${this.data.from}]`)
      .prop('selected', true);
  }

  whereActions() {
    const $where = this.$.form.find('.where');
    const $whereInput = $where.find('input');
    const $close = $('<a href="#" class="close"><i class="ion-ios-close-empty"></i></a>');

    $where.append($close);

    $close.on('click', () => {
      $whereInput.typeahead('val', '').focus();
      this.data.where.country = null;
      this.data.where.regions = [];
      this.data.where.hotels = null;
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

      const hotelsQuery = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: `${this.endpoint}hotels/?query=%QUERY`,
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
            return `<div><span>${item.name}</span> <i>${item.country_name}</i></div>`;
          },
        },
      }, {
        name: 'hotels',
        source: hotelsQuery,
        display: 'name',
        templates: {
          header: '<h3>Отели</h3><div class="suggestions">',
          footer: '</div>',
          suggestion(item) {
            return `<div><span>${item.name.toLowerCase()}</span><i> ${item.regionName}</i></div>`;
          },
        },
      }).on('typeahead:autocomplete typeahead:select', (e, object) => {
        let hotelId = null;
        let regionId = [];
        let countryId = null;

        if (object.isCountry) {
          countryId = parseInt(object.id, 10);
        }

        if (object.isRegion) {
          regionId = [parseInt(object.id, 10)];
          countryId = parseInt(object.country, 10);
        }

        if (object.isHotel) {
          hotelId = parseInt(object.id, 10);
          regionId = [parseInt(object.region, 10)];
          countryId = parseInt(object.country, 10);
        }

        $where.removeClass('error');
        this.data.where.country = countryId;
        this.data.where.regions = regionId;
        this.data.where.hotels = hotelId;
        $close.show();

        return false;
      }).on('typeahead:change', (e, value) => {
        $where.removeClass('error');

        if (
          countries.get(value).length === 0 &&
          regions.get(value).length === 0 &&
          !this.data.where.hotels
        ) {
          this.data.where.country = null;
          this.data.where.regions = [];
          this.data.where.hotels = null;
          $whereInput.typeahead('val', '');
          $close.hide();
        }
      });

      const country = this.data.where.country;
      const region = this.data.where.regions[0] || false;

      if (regionsList[region]) {
        $whereInput.typeahead('val', regionsList[region].name);
        $close.show();
      } else if (country) {
        $whereInput.typeahead('val', countriesList[country].name);
        $close.show();
      }

      this.formReady();
    });
  }

  whenActions() {
    const minDate = moment().add(1, 'days');
    const maxDate = moment().add(1, 'year');
    const dateFrom = moment(this.data.when.dateFrom, DATE_FORMAT);

    this.range = (this.data.when.dateFrom !== this.data.when.dateTo) ? DATE_RANGE : 0;

    if (this.range > 0) this.$.form.find('.when .range').show();

    const datepicker = this.$.form.find('.when input')
      .datepicker({
        minDate: minDate.toDate(),
        maxDate: maxDate.toDate(),
        onRenderCell: (date, cellType) => {
          const currentDate = moment(date);
          const startDate = moment(this.data.when.dateFrom, DATE_FORMAT);
          const endDate = moment(this.data.when.dateTo, DATE_FORMAT);

          if (
            cellType === 'day' &&
            (currentDate.isSameOrAfter(minDate) && currentDate.isBefore(maxDate)) &&
            (currentDate.isSameOrAfter(startDate) && currentDate.isSameOrBefore(endDate))
          ) {
            return {
              html: `<span class="selected">${currentDate.format('D')}</span>`,
            };
          }
          return currentDate.format('D');
        },
        onSelect: (formattedDate, date) => {
          const momentDate = moment(date);
          this.$.form.find('.when .value').text(momentDate.format(DATE_VISIBLE_FORMAT));
          const { from, to } = this.rangeToDate(momentDate);
          this.data.when.dateFrom = from;
          this.data.when.dateTo = to;
        },
      }).data('datepicker');

    const $rangePicker = $('<div class="range-checkbox" />');
    $rangePicker.append(
      `<input type="checkbox" id="date-range-days" value="1" name="date-range-days" ${(this.range === DATE_RANGE) ? 'checked="checked"' : ''}> 
       <label for="date-range-days">± ${DATE_RANGE} дня</label>`,
    );

    datepicker.$datepicker.append($rangePicker);

    $rangePicker.find('label').off('click').on('click', (e) => {
      const $el = $(e.target);
      const $input = $el.siblings('input[type="checkbox"]');

      if ($input.is(':checked')) {
        this.$.form.find('.when .range').hide();
        this.range = 0;
      } else {
        this.$.form.find('.when .range').show();
        this.range = DATE_RANGE;
      }

      datepicker.selectDate(datepicker.selectedDates[0]);
    });

    const selectedDate = moment(dateFrom);

    // TODO: add datepicker range
    datepicker.selectDate(selectedDate.add(this.range, 'days').toDate());
  }

  nightsActions() {
    let { range, nights } = SearchForm.nightsToRange(
      this.data.when.nightsFrom,
      this.data.when.nightsTo,
    );

    const limits = this.limits.nights;

    const $popup = this.$.nights.find('.popup');
    const $selector = $popup.find('.selector');
    const $range = $popup.find('.range-checkbox input');

    const setNights = (n, r) => {
      const { from, to } = SearchForm.rangeToNights(n, r);
      this.data.when.nightsFrom = from;
      this.data.when.nightsTo = to;
    };

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
      $popup.addClass('active');
      $selector.find('.minus').off('click').on('click', (e) => {
        const $el = $(e.target);
        if (!$el.hasClass('disabled')) {
          nights -= 1;
          this.setText('nights', nights);
          setNights(nights, range);
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
          setNights(nights, range);
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
          range = true;
          this.$.nights.find('.range').show();
        } else {
          range = false;
          this.$.nights.find('.range').hide();
        }
        setNights(nights, range);
      });

      return false;
    });
  }

  peopleActions() {
    const limits = this.limits.people;
    let adults = parseInt(this.data.people.adults, 10);
    const kids = this.data.people.children;
    let people = parseInt(adults, 10);
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
      this.setText('people', people);
      $el.parent().remove();

      if (kids.length >= limits.kids) {
        $kidsSelect.hide();
        $kidsAlert.show();
      } else {
        $kidsSelect.show();
        $kidsAlert.hide();
      }

      return false;
    };

    this.setText('adults', adults);
    this.setText('people', people);

    for (let i = 0; i < kids.length; i += 1) {
      const age = parseInt(kids[i], 10);
      const $kid = SearchForm.createKid($kidTemplate, Humanize.age(age), age, kidDelete);

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
      $popup.addClass('active');

      $adultsSelector.find('.minus').off('click').on('click', (e) => {
        const $el = $(e.target);

        if (!$el.hasClass('disabled')) {
          adults -= 1;
          people -= 1;

          this.data.people.adults = adults;
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

          this.data.people.adults = adults;
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

        const $kid = SearchForm.createKid($kidTemplate, $kidsSelect.find('option:selected').text(), age, kidDelete);

        this.$.people.find('.kids').append($kid);

        kids.push(age);
        people += 1;

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

  submitActions() {
    this.$.form.on('submit', () => false);

    this.$.form.find('.search-button button').on('click', (e) => {
      const $el = $(e.target);
      $el.prop('disabled', true);
      this.$.form.find('.loader').show();

      if (this.formCheck()) {
        const data = {
          from: this.data.from,
          where: this.data.where,
          when: this.data.when,
          people: this.data.people,
          filters: this.data.filters,
        };

        $.getJSON(`${this.endpoint}search/`, {
          params: data,
        }, (res) => {
          if (res.url) {
            window.location.href = res.url;
          }
        });
      }

      return false;
    });
  }

  /** Methods */

  static createKid(template, text, age, callback) {
    const $kid = template.clone();
    $kid.removeClass('template');
    $kid.find('span').text(text);
    $kid.attr('data-age', age);

    $kid.find('i').off('click').on('click', callback);
    return $kid;
  }

  formCheck() {
    const errors = [];

    if (!this.data.where.country && !this.data.where.regions.length) {
      errors.push('where');
    }

    if (errors.length > 0) {
      $.each(errors, (i, error) => {
        this.$.form.find(`.${error}`).addClass('error');
      });

      this.$.form.find('.search-button button').prop('disabled', false);
      this.$.form.find('.loader').hide();

      return false;
    }
    return true;
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
      this.$.people.find('.popup .selector .param span').text(value);
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

  static nightsToRange(from, to) {
    const range = (from !== to);
    return {
      range,
      nights: range ? from + DATE_RANGE : parseInt(from, 10),
    };
  }

  static rangeToNights(nights, range) {
    return {
      from: (range) ? nights - NIGHTS_RANGE : nights,
      to: (range) ? nights + NIGHTS_RANGE : nights,
    };
  }

  rangeToDate(momentDate) {
    return {
      from: momentDate.add(-this.range, 'days').format(DATE_FORMAT),
      to: momentDate.add(2 * this.range, 'days').format(DATE_FORMAT),
    };
  }
}
