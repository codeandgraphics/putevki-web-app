import $ from 'jquery';
import moment from 'moment';
import 'bootstrap-validator';

import { IS_DEV } from '../../app.es6';
import * as Humanize from '../utils/humanize.es6';
import { serializeForm } from '../utils/helpers.es6';

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
    this.$.part = this.$.flights.find('.part.template');
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
      const details = data.details;

      if (data.error.code !== 0 || details.actualized === false) {
        this.cannotActualize();
      } else {
        this.$.flights.removeClass('hide');

        if (details.flights.length === 0) {
          this.hasNoFlights();
        } else {
          this.basePrice = details.flights[0].price;
          const $firstFlight = this.buildFlight(details.flights[0]);

          if ($firstFlight) {
            $firstFlight.click();
            $items.append($firstFlight);

            if (details.flights.length > 1) {
              const $moreItems = this.$.flights.find('#more-flights');
              $variants.removeClass('hide');
              for (let i = 1; i < details.flights.length; i += 1) {
                const $flight = this.buildFlight(details.flights[i]);
                $moreItems.append($flight);
              }
            }
          } else {
            this.hasNoFlights();
          }
        }

        const flags = details.info.flags;
        for (const flag in flags) {
          if (flags.hasOwnProperty(flag)) {
            if (!flags[flag]) {
              $includes.find(`.flag-${flag}`).addClass('none');
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
      forward: flight.forward,
      backward: flight.backward,
    };

    $flight.css('height', 70 * Math.max(directions.forward.length, directions.backward.length));

    for (const dir in directions) {
      const $direction = $flight.find(`.${dir}`);
      const flights = directions[dir];

      flights.forEach((part) => {
        const $part = this.$.part.clone();
        $part.removeClass('template');

        $part.prop('title', `${part.company.name}, рейс ${part.number}, ${part.plane}`);
        $part.tooltip();

        const date = moment(flight[`date${dir}`], 'DD.MM.YYYY');

        const $departure = $part.find('.departure');
        const $arrival = $part.find('.arrival');

        if (date.isValid()) {
          $departure.find('.date').text(date.format('D MMMM'));
          $arrival.find('.date').text(date.format('D MMMM'));
        } else {
          $departure.find('.date').text('Уточняется');
          $arrival.find('.date').text('Уточняется');
        }

        const departureTime = moment(part.departure.time, 'HH:mm');
        $departure.find('.time').text(departureTime.format('HH:mm'));
        $departure.find('.airport').text(`${part.departure.port.id}, ${part.departure.port.name}`);

        const arrivalTime = moment(part.arrival.time, 'HH:mm');
        $arrival.find('.time').text((arrivalTime.isValid()) ? arrivalTime.format('HH:mm') : '??:??');
        $arrival.find('.airport').text(`${part.arrival.port.id}, ${part.arrival.port.name}`);

        $direction.append($part);
      });
    }

    $flight.find('.charge').html(`${flight.fuel.value} <span>руб.</span>`);

    const $fuel = $flight.find('.fuel');
    const diff = (this.basePrice + this.price.fuel) - flight.price;

    if (diff < 0) {
      $flight.find('.changed .more').removeClass('hide').text(`Дороже на ${Humanize.price(Math.abs(diff))} руб.`);
      $fuel.find('.data').addClass('is-changed');
    }
    if (diff > 0) {
      $flight.find('.changed .less').removeClass('hide').text(`Дешевле на ${Humanize.price(Math.abs(diff))} руб.`);
      $fuel.find('.data').addClass('is-changed');
    }

    $flight.off('click').on('click', () => {
      this.$.flights.find('.flight').removeClass('active');
      $flight.addClass('active');

      this.setPrice('fuel', flight.fuel.value);
      this.setPrice('price', flight.price);

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
    this.$.tour.find('.sidebar .content').stick_in_parent();

    $('#buy a').click(function buyClick(e) {
      if ($(this).parent().hasClass('disabled')) {
        e.preventDefault();
        return false;
      }
      e.preventDefault();
      $(this).tab('show');
      return false;
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

    this.$.forms.online.on('submit', (e) => {
      e.preventDefault();
      this.checkForm('online');
      return false;
    });

    this.$.forms.office.on('submit', (e) => {
      e.preventDefault();
      this.checkForm('office');
      return false;
    });

    this.$.forms.request.on('submit', (e) => {
      e.preventDefault();
      this.checkForm('request');
      return false;
    });

    this.$.flights.find('.variants').on('click', (e) => {
      const $el = $(e.target);
      $el.hide();
      $('#more-flights').collapse('show');
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

      const formData = serializeForm($form);

      formData.price = this.getFullPrice();
      formData.tour = this.tourData;
      formData.flight = this.transformFlight();

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
        this.$.prices.find('.tour-visa')
          .removeClass('hidden')
          .find('span')
          .text(`+ ${Humanize.price(this.price.visa * parseInt(this.tour.visa, 10))} руб.`);
      } else {
        this.$.prices.find('.tour-visa').addClass('hidden');
      }
    } else {
      this.price[type] = intPrice;

      if (type === 'price') {
        this.$.prices.find('.tour-price span').text(`${Humanize.price(this.price[type])} руб.`);
      } else if (this.price[type] >= 0) {
        this.$.prices.find(`.tour-${type}`).removeClass('hidden');
        this.$.prices.find(`.tour-${type} span`).text(`+ ${Humanize.price(this.price[type])} руб.`);
      }
    }
    let sum = this.price.price + this.price.fuel;

    sum += this.price.visa * this.tour.visa;

    if (type === 'price') { this.$.tour.find('.data input[name="price"]').val(price); }

    this.$.prices.find('.tour-sum strong').text(`${Humanize.price(sum)} руб.`);
  }

  getFullPrice() {
    let sum = this.price.price + this.price.fuel;
    sum += this.price.visa * this.tour.visa;

    return sum;
  }

  transformFlight() {
    const newFlight = Object.assign({}, this.flight);

    if(!this.flight) {
      return false;
    }

    if (this.flight.forward.length > 0 && this.flight.backward.length > 0) {
      newFlight.forward = [];
      newFlight.backward = [];

      const setItem = (item, isForward) => ({
        number: item.number,
        plane: item.plane,
        company: `${item.company.name} (${item.company.id})`,
        departure: {
          date: isForward ? this.flight.forwardDate : this.flight.backwardDate,
          time: item.departure.time,
          port: `${item.departure.port.name} (${item.departure.port.id})`,
        },
        arrival: {
          date: isForward ? this.flight.forwardDate : this.flight.backwardDate,
          time: item.arrival.time,
          port: `${item.arrival.port.name} (${item.arrival.port.id})`,
        },
      });

      this.flight.forward.forEach((item) => {
        newFlight.forward.push(setItem(item, true));
      });

      this.flight.backward.forEach((item) => {
        newFlight.backward.push(setItem(item, false));
      });
    }

    return newFlight;
  }

}
