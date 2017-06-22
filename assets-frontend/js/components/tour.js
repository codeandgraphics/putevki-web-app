import $ from 'jquery';
import moment from 'moment';

import { IS_DEV } from '../../app';

import * as Humanize from '../utils/humanize';

export default class Tour {

  constructor() {
    this.endpoint = '/ajax/';
    this.hotelLink = '/hotel/';

    this.startDate = new Date();

    this.debug = true;

    this.tour = {};
    this.flight = null;
    this.tourists = 0;
    this.$forms = [];

    this.branch = false;

    this.price = {
      price: 0,
      fuel: 0,
      visa: 0,
      infant: 0,
      addPrice: 0,
      base: 0,
    };

    this.basePrice = 0;

    this.$ = {
      tour: $('#tour'),
    };

    this.$.prices = this.$.tour.find('.checkout');

    this.$.loader = this.$.tour.find('.actualize');
    this.$.flights = this.$.tour.find('.flights');
    this.$.tourists = this.$.tour.find('.tourists');
    this.$.payVariants = this.$.tour.find('.pay-variants');
    this.$.later = this.$.tourists.find('#tourists-later');


    this.$.forms = {
      online: this.$.tour.find('#online-form'),
      request: this.$.tour.find('#request-form'),
      office: this.$.tour.find('#office-form'),
    };

    this.tourData = this.$.tour.data('tour');

    this.initTour();

    if (IS_DEV) console.log(`[ТУР] Инициализирован. ${this.startDate}`);
  }

  initTour() {
    this.tour.id = this.tourData.id;

    this.tour.fuel = (this.tourData.fuelcharge) ? this.tourData.fuelcharge : 0;
    this.tour.visa = (this.tourData.visacharge) ? this.tourData.visacharge : 0;

    this.tour.price = this.tourData.price - this.tour.fuel;

    this.setPrice('price', this.tour.price);

    this.setActions();

    if (this.tourData.departurecode === '99') {
      this.withoutFlight();
      this.$.payVariants.removeClass('locked');
      this.$.loader.addClass('hide');
    } else {
      this.loadFlights();
    }
  }

  loadFlights() {
    const $variants = this.$.flights.find('.variants');
    const $items = this.$.flights.find('.items');
    const $includes = this.$.tour.find('.includes');

    $.getJSON(this.$.flights.data('url'), (data) => {
      this.$.loader.addClass('hide');

      if (data.iserror) {
        this.cannotActualize();
      } else {
        this.$.flights.removeClass('hide');

        if (data.flights.length === 0) {
          this.hasNoFlights();
        } else {
          this.basePrice = data.flights[0].price.value;
          const $firstFlight = this.buildFlight(data.flights[0]);

          if ($firstFlight) {
            $firstFlight.click();
            $items.append($firstFlight);

            if (data.flights.length > 1) {
              const $moreItems = this.$.flights.find('#more-flights');
              $variants.removeClass('hide');
              for (let i = 1; i < data.flights.length; i += 1) {
                const $flight = this.buildFlight(data.flights[i]);
                $moreItems.append($flight);
              }
            }
          } else {
            this.hasNoFlights();
          }
        }

        if (data.tourinfo.flags != null) {
          const flags = data.tourinfo.flags;
          for (const flag in flags) {
            if (flags.hasOwnProperty(flag)) {
              if (flags[flag]) {
                $includes.find(`.${flag}`).addClass('none');
              }
            }
          }
        }
      }

      this.$.payVariants.removeClass('locked');
    });
  }

  buildFlight(flight) {
    if (flight.forward.length < 1 || flight.backward.length < 1) {
      return false;
    }

    const $flight = this.$.flights.find('.flight.template').clone();
    $flight.removeClass('template');

    const directions = {
      forward: flight.forward[0],
      backward: flight.backward[0],
    };

    for (const dir in directions) {
      const $direction = $flight.find(`.${dir}`);
      const direction = directions[dir];

      $direction.prop('title', `${direction.company.name}, рейс ${direction.number}, ${direction.plane}`);
      $direction.tooltip();

      const date = moment(flight[`date${dir}`], 'DD.MM.YYYY');

      const $departure = $direction.find('.departure');
      const $arrival = $direction.find('.arrival');

      if (date.isValid()) {
        $departure.find('.date').text(date.format('D MMMM'));
        $arrival.find('.date').text(date.format('D MMMM'));
      } else {
        $departure.find('.date').text('Уточняется');
        $arrival.find('.date').text('Уточняется');
      }

      const departureTime = moment(direction.departure.time, 'HH:mm');
      $departure.find('.time').text(departureTime.format('HH:mm'));
      $departure.find('.airport').text(`${direction.departure.port.id}, ${direction.departure.port.name}`);

      const arrivalTime = moment(direction.arrival.time, 'HH:mm');
      $arrival.find('.time').text((arrivalTime.isValid()) ? arrivalTime.format('HH:mm') : '??:??');
      $arrival.find('.airport').text(`${direction.arrival.port.id}, ${direction.arrival.port.name}`);
    }

    $flight.find('.charge').html(`${flight.fuelcharge.value} <span>руб.</span>`);

    const $fuel = $flight.find('.fuel');
    if (this.basePrice < flight.price.value) {
      $fuel.find('.changed .more').removeClass('hide');
      $fuel.find('.data').addClass('is-changed');
    }
    if (this.basePrice > flight.price.value) {
      $fuel.find('.changed .less').removeClass('hide');
      $fuel.find('.data').addClass('is-changed');
    }

    $flight.off('click').on('click', () => {
      this.$.flights.find('.flight').removeClass('active');
      $flight.addClass('active');

      this.setPrice('fuel', flight.fuelcharge.value);
      this.setPrice('price', flight.price.value);

      this.flight = flight;
    });

    return $flight;
  }

  cannotPay() {
    this.$.tour.find('.no-online').removeClass('hide');
    this.$.payVariants.find('.variant.online').addClass('disabled');
    this.$.payVariants.find('.variant.request a').click();
  }

  cannotActualize() {
    this.$.tour.find('.no-actualize').removeClass('hide');
    this.cannotPay();
  }

  withoutFlight() {
    this.$.flights.find('.no-flights').removeClass('hide');
  }

  hasNoFlights() {
    this.$.flights.find('.no-flights').removeClass('hide');
    this.cannotPay();
  }

  setActions() {
    // $('input[data-inputmask], input[data-inputmask-regex]').inputmask();

    this.$.tour.find('.sidebar .content').stick_in_parent({
      offset_top: 80,
    });

    $('#buy a').click(function (e) {
      if ($(this).parent().hasClass('disabled')) {
        e.preventDefault();
        return false;
      }
      e.preventDefault();
      $(this).tab('show');
    });

    this.$.later.on('change', (e) => {
      const $el = $(e.target);
      const checked = $el.prop('checked');

      if (checked) {
        this.$.tourists.find('.items').collapse('hide').on('hidden.bs.collapse', () => {
          this.$.tourists.find('.later').show();
        });
        this.$.tourists.find('.items input').prop('required', false);
        this.$.tourists.find('.items .has-error').removeClass('has-error');
      } else {
        this.$.tourists.find('.later').hide();
        this.$.tourists.find('.items').collapse('show');
        this.$.tourists.find('.items input').prop('required', true);
      }
    });

    this.$.forms.online.on('submit', () => {
      this.checkForm('online');
      return false;
    });

    this.$.forms.office.on('submit', () => {
      this.checkForm('office');
      return false;
    });

    this.$.forms.request.on('submit', () => {
      this.checkForm('request');
      return false;
    });

    this.$.flights.find('.variants').on('click', (e) => {
      const $el = $(e.target);
      $el.hide();
      $('#more-flights').collapse('show');
      return false;
    });

    this.$.flights.find('.flight').off('click').on('click', (e) => {
      const $el = $(e.target);
      if ($el.not('.active')) {
        this.$.flights.find('.flight').removeClass('active');
        $el.addClass('active');

        const fuel = parseInt($el.data('fuel'), 10);
        const price = parseInt($el.data('price'), 10) - fuel;

        this.setPrice('fuel', fuel);
        this.setPrice('price', price);

        this.flight = $el.data('flight-id');
      }

      return false;
    });

    this.$.tourists.find('.tourist .visa input').on('change', (e) => {
      const $el = $(e.target);
      const checked = $el.is(':checked');
      this.setPrice('visa', checked ? 1 : -1);
    });
  }

  checkForm(type) {
    const $form = this.$.forms[type];

    $form.validator('validate');

    if ($form.find('#confirmation').prop('checked')) {
      $form.find('#confirmation').parent().removeClass('has-error');
    } else {
      $form.find('#confirmation').parent().addClass('has-error');
    }

    if ($form.find('.form-group.has-error').length > 0) {
      const $input = $form.find('.form-group.has-error').first().find('input');
      $input.focus();
      $(window).scrollTo($input, 300, {
        offset: -120,
      });
    } else {
      $form.find('button[type=submit]').prop('disabled', true).addClass('disabled');

      const formData = $form.serializeObject();

      formData.price = this.tourData.price;
      formData.tour = this.tourData;
      formData.flight = this.flight;

      if (type === 'online') {
        this.sendOnline(formData, type);
      }

      if (type === 'request') {
        this.sendOnline(formData, type);
      }

      if (type === 'office') {
        formData.branch = global.branch;
        this.sendOnline(formData, type);
      }

      console.log(formData);
    }
  }

  sendOnline(data, type) {
    $('#onlineStatusModal').modal({
      backdrop: 'static',
      keyboard: false,
    }).addClass('loading').addClass(type);

    $.post('/ajax/formOnline', { data: JSON.stringify(data), type }, (response) => {
      setTimeout(() => {
        if (response.res === '') {
          $('#onlineStatusModal').removeClass('loading').addClass('error');

          setTimeout(() => {
            $('#onlineStatusModal').modal('hide');
            this.$.tour.prop('disabled', false).removeClass('disabled');
          }, 2000);
        } else {
          $('#onlineStatusModal').removeClass('loading error').addClass('success');

          setTimeout(() => {
            $('#onlineStatusModal').modal('hide');
            window.location.href = response.res;
          }, 2000);
        }
      }, 1500);
    }, 'json');
  }

  setPrice(type, price) {
    const intPrice = parseInt(price, 10);

    if (type === 'visa') {
      this.price.visa += intPrice;

      if (this.price.visa > 0 && this.tour.visa > 0) {
        this.$.prices.find('.tour-visa').removeClass('hidden');
        this.$.prices.find('dd.tour-visa').text(`+ ${Humanize.price(this.price.visa * parseInt(this.tour.visa, 10))} руб.`);
      } else {
        this.$.prices.find('.tour-visa').addClass('hidden');
      }
    } else {
      this.price[type] = intPrice;

      if (type === 'price') {
        this.$.prices.find('dd.tour-price').text(`${Humanize.price(this.price[type])} руб.`);
      } else if (this.price[type] >= 0) {
        this.$.prices.find(`.tour-${type}`).removeClass('hidden');
        this.$.prices.find(`dd.tour-${type}`).text(`+ ${Humanize.price(this.price[type])} руб.`);
      }
    }
    let sum = this.price.price + this.price.fuel;

    sum += this.price.visa * this.tour.visa;

    if (type === 'price') { this.$.tour.find('.data input[name="price"]').val(price); }

    this.$.prices.find('.tour-sum strong').text(`${Humanize.price(sum)} руб.`);
  }
}
