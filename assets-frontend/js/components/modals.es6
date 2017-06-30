import $ from 'jquery';
import Bloodhound from 'typeahead.js';
import 'bootstrap-validator';

import { IS_DEV } from '../../app.es6';
import { serializeForm } from '../utils/helpers.es6';

class FindTourModal {
  constructor() {
    this.$ = {
      modal: $('#findTourModal'),
    };

    this.$.findDeparture = this.$.modal.find('#find-departure');
    this.$.whereInput = this.$.modal.find('.where input');

    this.departures = this.$.modal.data('departures').split(',');
    this.countries = this.$.modal.data('countries').split(',');
    this.regions = this.$.modal.data('regions').split(',');
    this.from = this.$.modal.data('from');
    this.fromId = this.$.modal.data('from-id');

    this.data = {};

    this.endpoint = '/ajax/';
    this.debug = (IS_DEV);
  }

  init() {
    this.initDepartures();
    this.initWhere();
    this.initTypes();
    this.initFields();

    const self = this;

    this.$.modal.find('#sendFind').on('click', function clickSend(e) {
      self.sendAction($(this));
      e.preventDefault();
      return false;
    });

    this.setValue('departure', self.fromId);
    this.setValue('from', self.from);
  }

  sendAction($el) {
    let error = 0;

    if (!this.data.name) {
      this.$.modal.find('#find-name').focus().parent().addClass('has-error');
      error += 1;
    } else {
      this.$.modal.find('#find-name').parent().removeClass('has-error');
    }
    if (!this.data.phone) {
      this.$.modal.find('#find-phone').focus().parent().addClass('has-error');
      error += 1;
    } else {
      this.$.modal.find('#find-phone').parent().removeClass('has-error');
    }
    if (!this.data.email) {
      this.$.modal.find('#find-email').focus().parent().addClass('has-error');
      error += 1;
    } else {
      this.$.modal.find('#find-email').parent().removeClass('has-error');
    }

    if (error === 0) {
      const type = 'find';

      this.$.modal.modal('hide');

      const dataString = JSON.stringify(this.data);


      $('#onlineStatusModal').modal({
        backdrop: 'static',
        keyboard: false,
      }).addClass('loading').addClass(type);

      if (IS_DEV) console.log(dataString);

      $.post('/ajax/findTour', { data: dataString, type }, () => {
        setTimeout(() => {
          $('#onlineStatusModal').removeClass('loading').addClass('success');

          setTimeout(() => {
            $('#onlineStatusModal').modal('hide');
            $el.prop('disabled', false).removeClass('disabled');
          }, 2000);
        }, 1500);
      }, 'json');
    }

    return false;
  }

  initTypes() {
    this.$.modal.find('.types input').on('change', (e) => {
      const $item = $(e.target);
      this.setValue($item.val(), ($item.prop('checked')));
    });
  }

  initFields() {
    this.$.modal.find('input[type=text]').on('change', (e) => {
      const $item = $(e.target);
      this.setValue($item.attr('id').replace('find-', ''), $item.val());
    });
  }

  initDepartures() {
    $.getJSON(`${this.endpoint}departures/`, (data) => {
      const departuresList = [];

      $.each(data.departures, (i, departure) => {
        departuresList[departure.id] = departure.name;
      });

      const departures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify(obj) { return obj.name; },
        local: data.departures,
      });

      function departuresDefault(q, sync) {
        if (q === '') {
          sync(departures.get(self.departures));
        } else {
          departures.search(q, sync);
        }
      }

      this.$.findDeparture.typeahead({
        hint: true,
        highlight: true,
        minLength: 0,
      }, {
        name: 'departures',
        source: departuresDefault,
        display: 'name',
        displayKey: 'id',
        templates: {
          header: '<h3>Город</h3>',
          suggestion(item) {
            return `<div>${item.name}</div>`;
          },
        },
      }).on('typeahead:autocomplete typeahead:select', (e, object) => {
        this.setValue('departure', object.id);
        this.setValue('from', departuresList[object.id]);
        return false;
      }).on('typeahead:change', (e, value) => {
        if (departures.get(value).length === 0) {
          this.setValue('departure', false);
          this.setValue('from', false);
          this.$.findDeparture.typeahead('val', '');
        }
      });
    });
  }

  initWhere() {
    $.getJSON(`${this.endpoint}destinations/`, (data) => {
      const countriesList = [];
      const regionsList = [];

      $.each(data.countries, (i, country) => {
        countriesList[country.id] = country.name;
      });

      $.each(data.regions, (i, region) => {
        regionsList[region.id] = region;
      });

      const countries = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify(obj) { return obj.name; },
        local: data.countries,
      });

      const regions = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify(obj) { return obj.name; },
        local: data.regions,
      });

      function countriesDefault(q, sync) {
        if (q === '') {
          sync(countries.get(this.countries));
        } else {
          countries.search(q, sync);
        }
      }

      function regionsDefault(q, sync) {
        if (q === '') {
          sync(regions.get(this.regions));
        } else {
          regions.search(q, sync);
        }
      }

      this.$.whereInput.typeahead({
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
            return `<div>${item.name}</div>`;
          },
        },
      }, {
        name: 'regions',
        source: regionsDefault,
        display: 'name',
        displayKey: 'id',
        templates: {
          header: '<h3>Регионы</h3><div class="suggestions">',
          footer: '</div>',
          suggestion(item) {
            return `<div>${item.name} <span class="country"> ${countriesList[item.country]}</span></div>`;
          },
        },
      }).on('typeahead:autocomplete typeahead:select', (e, object) => {
        this.setValue('country', (object.country) ? object.country : object.id);
        this.setValue('region', (object.country) ? object.id : false);

        let where = object.name;
        if (object.country) { where = `${countriesList[object.country]}(${object.name})`; }

        this.setValue('where', where);

        return false;
      }).on('typeahead:change', (e, value) => {
        if (countries.get(value).length === 0) {
          if (regions.get(value).length === 0) {
            this.setValue('country', false);
            this.setValue('region', false);
            this.setValue('where', false);
            this.$.whereInput.typeahead('val', '');
          }
        }
      });
    });
  }

  setValue(key, value) {
    this.data[key] = value;

    if (value) {
      this.$.modal.find(`#selected dt.text-${key}`).removeClass('hide');
      this.$.modal.find(`#selected dd.${key}`).removeClass('hide');
    } else {
      this.$.modal.find(`#selected dt.text-${key}`).addClass('hide');
      this.$.modal.find(`#selected dd.${key}`).addClass('hide');
    }

    if (IS_DEV) console.log(['beach', 'excursion', 'skiing'].indexOf(key));

    if (['beach', 'excursion', 'skiing'].indexOf(key) !== -1) {
      if (this.data.beach || this.data.excursion || self.data.skiing) {
        this.$.modal.find('#selected dt.text-types').removeClass('hide');
        this.$.modal.find('#selected dd.types').removeClass('hide');
      } else {
        this.$.modal.find('#selected dt.text-types').addClass('hide');
        this.$.modal.find('#selected dd.types').addClass('hide');
      }
      this.$.modal.find(`#selected dd.types .${key}`).toggleClass('hide', !value);
    } else {
      this.$.modal.find(`#selected dd.${key}`).text(value);
    }

    if (IS_DEV) console.log(JSON.stringify(this.data), this.data);
  }
}

export default class Modals {
  constructor() {
    this.$ = {
      callBackModal: $('#callBackModal'),
    };
  }

  init() {
    const findTour = new FindTourModal();
    findTour.init();

    this.initCallBackModal();
  }

  initCallBackModal() {
    this.$.callBackModal.find('form').on('submit', function submitForm(e) {
      e.preventDefault();
      const $el = $(this);

      $el.validator('validate');

      if ($el.find('.form-group.has-error').length > 0) {
        const $input = $el.find('.form-group.has-error').first().find('input');
        $input.focus();
        $(window).scrollTo($input, 300, {
          offset: -120,
        });
      } else {
        const formData = serializeForm($el);

        $(this).prop('disabled', true).addClass('disabled');

        $('#callBackModal').addClass('loading');

        $.post('/ajax/tourHelp', { data: formData }, (response) => {
          if (response.status === 'ok') {
            $('#callBackModal').removeClass('loading').addClass('success');
          } else {
            $('#callBackModal').removeClass('loading').addClass('error');
            $(this).prop('disabled', false).removeClass('disabled');
          }
        }, 'json');
      }

      return false;
    });
  }
}
