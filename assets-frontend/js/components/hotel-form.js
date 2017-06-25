import $ from 'jquery';
import moment from 'moment';

import * as Humanize from '../utils/humanize';
import { IS_DEV } from '../../app';

export default class HotelForm {

  constructor(formObject) {
    this.formObject = formObject;
    this.tourvisorId = null;
    this.tourlink = '/tour/';

    this.hasFirst = false;
    this.firstResults = [];
    this.allResults = [];
    this.isSearching = false;

    this.debug = (IS_DEV);

    this.$ = {
      el: $('.tours .results'),
    };

    this.$.variants = this.$.el.find('.variants');
    this.$.loader = this.$.el.find('.loader');
    this.$.no = this.$.el.find('.no-results');
    this.$.more = this.$.el.find('.more');
    this.$.template = this.$.el.find('.variant.template');

    this.$.no.hide();
    this.$.loader.show();

    this.$.more.find('a').on('click', () => {
      this.$.variants.find('.variant').show(100);
      this.$.more.hide();
      return false;
    });
  }

  start(searchId) {
    this.searchId = searchId;
    this.allResults = [];
    this.firstResults = [];
    this.hasFirst = false;
    this.isSearching = true;
    this.getStatus();
  }

  getStatus() {
    if (this.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Получаем статус: ', new Date());

    $.getJSON(`${this.formObject.endpoint}status/${this.searchId}`, (res) => {
      this.formObject.$.form.find('.progressbar').css('width', `${res.status.progress}%`);
      if (res.status.state === 'finished') {
        this.isSearching = false;
        this.$.loader.hide();

        this.getResults(false, () => {
          if (this.hasFirst) {
            this.$.more.show();
          } else {
            this.showResults();
          }
        });
      } else {
        if (res.status.state === 'searching') {
          if (res.status.toursfound > 1 && !this.hasFirst) {
            this.getResults(true);
          }
        }

        setTimeout(() => {
          this.getStatus();
        }, 3000);
      }
      if (this.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Статус: ', res.status);
    });
  }

  getResults(first, callback) {
    if (this.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Получаем результаты: ', new Date());

    $.getJSON(`${this.formObject.endpoint}results/${this.searchId}`, (res) => {
      if (res.status.toursfound > 0) {
        if (first) {
          this.firstResults = res.hotels[0].tours;
          this.hasFirst = true;
          this.showResults();
        } else {
          this.allResults = res.hotels[0].tours;

          callback();

          if (this.allResults.length === this.firstResults.length) {
            this.$.more.hide();
          }
        }
      }
    });
  }

  showResults() {
    this.$.loader.hide();

    if (this.isSearching) {
      if (this.hasFirst) {
        this.buildResults(this.firstResults);
      }
    } else if (this.allResults.length > 0) {
      this.buildResults(this.allResults);
      this.formObject.$.form.find('.progressbar').css('width', '0%');
    } else {
      this.$.no.show();
      this.formObject.$.form.find('.progressbar').css('width', '0%');
    }
  }

  buildResults(tours) {

    this.$.variants.find('.variant:not(.template)').remove();

    $.each(tours, (i, tour) => {
      const $variant = this.$.template.clone();

      $variant.removeClass('template');

      $variant.attr('data-price', tour.price);

      $variant.find('.price a').text(`${Humanize.price(tour.price)} р.`).attr('href', self.tourlink + tour.tourid);

      const dateTo = moment(tour.flydate, 'DD.MM.YYYY');
      $variant.find('.date span').text(dateTo.format('D MMMM'));
      $variant.find('.date small').text(Humanize.nights(tour.nights));

      $variant.find('.room span').text(tour.room);

      $variant.find('.meal span').text(tour.mealrussian);

      $variant.find('.operator .icon img')
        .attr('src', $variant.find('.operator .icon img').data('src').replace('{id}', tour.operatorcode))
        .attr('alt', tour.operatorname);

      $variant.find('.operator span').text(tour.operatorname);

      self.$variants.append($variant);
    });

    if (self.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Завершили рендер: ', new Date());
  };

}