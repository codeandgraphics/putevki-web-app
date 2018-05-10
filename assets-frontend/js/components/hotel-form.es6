import $ from 'jquery';
import moment from 'moment';

import * as Humanize from '../utils/humanize.es6';
import { IS_DEV } from '../../app.es6';

export default class HotelForm {

  constructor(formObject) {
    this.formObject = formObject;
    this.searchId = null;
    this.tourlink = '/tour/';

    this.hasFirst = false;
    this.firstResults = [];
    this.allResults = [];
    this.isSearching = false;

    this.debug = (IS_DEV);

    this.$ = {
      el: $('.tours .results'),
      params: $('.tours .params')
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

    this.bindFilters();
  }

  start(searchId) {
    this.searchId = searchId;
    this.allResults = [];
    this.firstResults = [];
    this.hasFirst = false;
    this.isSearching = true;
    this.getStatus();
  }

  bindFilters() {
    const self = this;

    const $meals = this.$.params.find('.meals');

    $meals.find('a').on('click', function mealsClick() {
      const $el = $(this);
      const html = $el.html();
      const meal = $el.data('meal');
      $meals.find('button .text').html(html).find('small').hide();

      self.formObject.data.filters.meal = meal;
      self.formObject.$.form.find('.search-button button').click();

      $meals.removeClass('open');
      return false;
    });
  }

  getStatus() {
    if (this.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Получаем статус: ', new Date());

    $.getJSON(`/api/searchStatus/?searchId=${this.searchId}`, (res) => {
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
          if (res.status.tours > 1 && !this.hasFirst) {
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

    $.getJSON(`/api/searchResult/?searchId=${this.searchId}`, (res) => {
      if (res.status.tours > 0) {
        if (first) {
          this.firstResults = JSON.parse(res.hotels[0].tours);
          this.hasFirst = true;
          this.showResults();
        } else {
          this.allResults = JSON.parse(res.hotels[0].tours);

          callback();

          if (this.allResults.length === this.firstResults.length) {
            this.$.more.hide();
          }

          if(this.allResults.length === 0) {
            this.$.no.show();
          }
        }
      } else {
        this.$.no.show();
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

      $variant.find('.price a').text(`${Humanize.price(tour.price)} р.`).attr('href', this.tourlink + tour.id);

      const dateTo = moment(tour.date, 'DD.MM.YYYY');
      $variant.find('.date span').text(dateTo.format('D MMMM'));
      $variant.find('.date small').text(Humanize.nights(tour.nights));

      $variant.find('.room span').text(tour.room);

      $variant.find('.meal span').text(tour.meal.russian);

      $variant.find('.operator .icon img')
        .attr('src', $variant.find('.operator .icon img').data('src').replace('{id}', tour.operator.id))
        .attr('alt', tour.operator.name);

      $variant.find('.operator span').text(tour.operator.name);

      this.$.variants.append($variant);
    });

    if (IS_DEV) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Завершили рендер: ', new Date());
  }

}
