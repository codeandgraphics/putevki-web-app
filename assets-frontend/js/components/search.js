import $ from 'jquery';
import moment from 'moment';
import 'ion-rangeslider';
import 'sticky-kit/dist/sticky-kit';

import { IS_DEV } from '../../app';
import { isScrolledIntoView } from '../utils/helpers';
import * as Humanize from '../utils/humanize';

export default class Search {
  constructor(requestId, formObject) {
    this.endpoint = '/ajax/';
    this.tourlink = '/tour/';
    this.link = '/hotel/';
    this.requestId = requestId;
    this.status = 'searching';

    this.typesMask = false;

    this.formObject = formObject;

    this.limit = 25;
    this.shown = 0;

    this.startDate = new Date();

    this.firstDelay = 1000;
    this.smallDelay = 3000;
    this.bigDelay = 4000;

    this.current = 'first';

    this.first = {
      hotels: [],
      hotelsTotal: 0,
      toursTotal: 0,
      minPrice: 0,
      shown: false,
    };

    this.all = {
      hotels: [],
      hotelsTotal: 0,
      hotelsLoaded: 0,
      page: 1,
      toursTotal: 0,
      minPrice: 0,
      loading: true,
      done: false,
      shown: false,
    };

    this.filters = {
      params: {
        stars: [],
        meals: [],
        maxPrice: 0,
        minPrice: 9999999,
        name: '',
      },
    };

    this.$ = { search: $('#search') };
    this.$.filters = this.$.search.find('.filters');
    this.$.filtersOverlay = this.$.search.find('.overlay');
    this.$.params = this.$.search.find('.params');
    this.$.template = this.$.search.find('.item.template');
    this.$.list = this.$.search.find('.tours.list');
    this.$.items = this.$.list.children('.items');
    this.$.variant = this.$.template.find('.variants .variant.template');
    this.$.loader = this.$.search.find('.progressbar .loader');

    this.$.process = this.$.search.find('.search-process');

    this.$.moreResults = this.$.search.find('.more-results');
    this.$.noResults = this.$.search.find('.no-results');
    this.$.help = this.$.search.find('.help');

    this.$.priceSlider = this.$.filters.find('#price');


    this.noImage = this.$.search.data('no-image');

    if (this.requestId) {
      setTimeout(() => {
        this.getStatus();
      }, this.firstDelay);

      this.$.loader.show();

      if (IS_DEV) console.log('[ПОИСК] Запускаем поиск', self.startDate);
    } else {
      this.notFound();
      if (IS_DEV) console.log('[ПОИСК] Нет данных для поиска', self.startDate);
    }

    $(window).on('scroll', () => {
      this.checkVisible();
    });

    this.$.moreResults.find('a').on('click', () => {
      this.showNext();
      return false;
    });

    this.$.search.find('.show-finished').off('click').on('click', () => {
      this.showFinished();
      return false;
    });

    this.bindFiltersActions();
  }

  getStatus() {
    $.getJSON(`/api/searchStatus/?searchId=${this.requestId}`, (data) => {
      this.processStatus(data.status);
    });
  }

  getResults(limit, callback) {
    $.getJSON(`/api/searchResult/?searchId=${this.requestId}&limit=${limit}`, (data) => {
      this.typesMask = data.typesMask.split(';');
      callback(data);
    });
  }

  processStatus(status) {
    this.status = status.state;

    this.setProgress(status);

    if (this.status === 'searching') {
      if (status.tours > 0 && !this.first.shown) {
        this.first.hotelsTotal = status.hotels;
        this.first.toursTotal = status.tours;
        this.first.minPrice = status.price.min;

        if (IS_DEV) console.log(`[ПОИСК] Нашел туры, получаем первые результаты. Прошло с начала: ${new Date() - self.startDate}мс`);

        this.getResults(this.first.hotelsTotal, (data) => {
          if (IS_DEV) console.log(`[ПОИСК] Получили первые результаты, ${this.first.hotelsTotal}${Humanize.hotelsFound(status.hotels)}. Прошло с начала: ${new Date() - this.startDate}мс`);

          this.first.hotels = data.hotels;
          this.renderFirst();
        });
      }

      const delay = (this.first.shown) ? this.bigDelay : this.smallDelay;

      setTimeout(() => {
        if (IS_DEV) console.log(`[ПОИСК] Обновляем статус, задержка ${delay}. Прошло с начала: ${new Date() - this.startDate}мс`);
        this.getStatus();
      }, delay);
    }

    if (this.status === 'finished') {
      if (status.tours === 0) {
        if (IS_DEV) console.log('[ПОИСК] Туры не найдены');

        this.notFound();
      } else {
        if (IS_DEV) console.log(`[ПОИСК] Поиск на сервере завершен, получаем результаты. Прошло с начала: ${new Date() - this.startDate}мс`);

        this.all.hotelsTotal = parseInt(status.hotels, 10);
        this.all.toursTotal = parseInt(status.tours, 10);
        this.all.minPrice = parseInt(status.price.min, 10);

        this.getResults(this.limit, (data) => {
          if (IS_DEV) console.log(`[ПОИСК] Получили финальные результаты. Прошло с начала: ${new Date() - this.startDate}мс`);

          this.$.loader.hide();

          this.all.hotels = data.hotels;
          this.all.hotelsLoaded += data.hotels.length;
          this.all.done = (this.all.hotelsLoaded >= this.all.hotelsTotal);

          this.stopSearch();

          this.all.loading = false;
        });
      }
    }
  }

  checkVisible() {
    if (this.all.shown && !this.all.done) {
      if (isScrolledIntoView(this.$.moreResults) && !this.all.loading) {
        this.showNext();
      }
    }
  }

  getFinishedPage(page) {
    $.getJSON(`/api/searchResult/?searchId=${this.requestId}&page=${page}`, (data) => {
      if (data.hotels) {
        this.all.hotels = data.hotels;
        this.all.hotelsLoaded += data.hotels.length;
        this.all.done = (this.all.hotelsLoaded >= this.all.hotelsTotal);

        this.renderFinished();
      }

      this.all.loading = false;
    });
  }

  showNext() {
    this.all.loading = true;
    this.$.moreResults.find('a').hide().siblings('.loader').show();
    this.all.page += 1;
    this.getFinishedPage(this.all.page);
    if (IS_DEV) console.log('[ПОИСК] Показываем следующую страницу');
  }

  setProgress(status) {
    this.$.search.find('.progressbar .bar').animate({
      width: `${status.progress}%`,
    }, 300);

    this.$.search.find('.progressbar .percent .count').text(status.hotels);
    this.$.search.find('.progressbar .percent .text').text(Humanize.hotelsFound(status.hotels));
  }

  stopSearch() {
    if (this.first.shown) {
      const priceDiff = this.first.minPrice - this.all.minPrice;
      const toursDiff = this.all.toursTotal - this.first.toursTotal;

      this.$.process.find('.tours-found').text(Humanize.tours(toursDiff));

      if (priceDiff > 0) {
        this.$.process
          .find('.cheaper-found')
          .show()
          .find('.price-found')
          .text(`${Humanize.price(priceDiff)} р.`);
      } else {
        this.$.process
          .find('.other-found')
          .show()
          .find('.price-found')
          .text(`${Humanize.price(this.all.minPrice)} р.`);
      }

      this.$.process.show(300);
    } else {
      this.showFinished();
    }
  }

  notFound() {
    this.$.noResults.show();
    this.$.moreResults.hide();

    this.$.filters.find('.wrap').hide();
    this.$.loader.hide();

    this.$.help.show();
  }

  renderFirst() {
    this.first.shown = true;
    this.showMore(false);
    this.renderHotels(this.first.hotels);

    this.$.filtersOverlay.hide();
  }

  renderFinished() {
    this.all.shown = true;
    this.renderHotels(this.all.hotels);
    this.showMore(!this.all.done);

    this.$.filtersOverlay.hide();
  }

  showFinished() {
    this.$.process.hide(300);

    this.shown = 0;
    this.current = 'finished';

    this.$.items.find('.item').remove();

    this.renderFinished();

    this.$.search.find('.show-finished').off('click');
  }

  renderHotels(hotels) {
    const renderTime = new Date();

    if (IS_DEV) console.log(`[ПОИСК] Рендерим результаты. Прошло с начала: ${new Date() - this.startDate}мс`);

    $.each(hotels, (i, hotel) => {
      const tours = JSON.parse(hotel.tours);
      if (tours.length > 0) {
        const $item = this.buildHotel(hotel);
        this.populateTours($item, tours);
        console.log(this.filters.params);
        this.$.items.append($item);
      }
    });

    this.$.moreResults.find('.loader').hide();
    this.$.moreResults.find('a').show();

    this.checkVisible();

    if (IS_DEV) console.log(`[ПОИСК] Завершили рендер результатов за ${new Date() - renderTime}мс. Прошло с начала: ${new Date() - this.startDate}мс`);

    this.rebuildFilters();
  }

  buildHotel(item) {
    const hotel = item;
    const $item = this.$.template.clone();
    $item.removeClass('template');

    $item.attr('data-hotel', hotel.id);
    $item.attr('data-name', hotel.name.toLowerCase());
    $item.attr('data-stars', hotel.stars);

    $item.find('.place span').text(`${hotel.country.name}, ${hotel.region.name}`);

    if (hotel.types && this.typesMask) {
      this.typesMask.forEach((type, i) => {
        const enabled = parseInt(hotel.types[i], 10);
        $item.attr(`data-${type}`, enabled);

        const $types = $item.find('.types');

        if (enabled) {
          const isDeluxe = type === 'deluxe' ? ' deluxe' : '';
          $types.append(`<li class="type${isDeluxe}">${Humanize.types(type)}</li>`);
        }
      });
    }

    $item.find('.title a').text(hotel.name.toLowerCase()).attr('href', hotel.link);

    if (!hotel.picture) { hotel.picture = this.noImage; }

    $item.find('.image .bg').css('background-image', `url(${hotel.picture})`);
    $item.find('.image a').attr('href', hotel.link);
    $item.find('.about .description').text(hotel.description);

    $item.find('.sum span').text(Humanize.price(hotel.price));

    const $stars = $item.find('.stars');
    const stars = parseInt(hotel.stars, 10);

    for (let s = 0; s < 5; s += 1) {
      $stars.append((s < stars) ? '<i class="star ion-ios-star"></i>' : '<i class="no-star ion-ios-star-outline"></i>');
    }

    if (hotel.rating !== 0) {
      $item.find('.review strong').text(hotel.rating);
      $item.find('.review span').text(Humanize.rating(hotel.rating));
    } else {
      $item.find('.review').hide();
    }

    return $item;
  }

  populateTours($item, tours) {
    $item.find('.variants .variant').not('.template').remove();

    $item.find('.more').removeClass('hidden').off('click').on('click', function more() {
      const $el = $(this);
      $el.addClass('hidden');
      $item.find('.variants .variant').not('.template').show(100);
      return false;
    });

    $item.find('.variants-open').off('click').on('click', function variantsOpen() {
      const $el = $(this);
      $el.siblings('.variants-close').show();
      $el.hide();
      $item.find('.variants').show(100);
      return false;
    });

    $item.find('.variants-close').off('click').on('click', function variantsClose() {
      const $el = $(this);
      $el.siblings('.variants-open').show();
      $el.hide();
      $item.find('.variants').hide();
      return false;
    });

    if (tours.length <= 5) $item.find('.more').addClass('hidden');

    $.each(tours, (i, tour) => {
      const $variant = this.$.variant.clone();

      if (tour.price < this.filters.params.minPrice) {
        this.filters.params.minPrice = parseInt(tour.price, 10);
      }
      if (tour.price > this.filters.params.maxPrice) {
        this.filters.params.maxPrice = parseInt(tour.price, 10);
      }

      $variant.removeClass('template');
      if (i > 4) $variant.hide();

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

      $item.find('.variants .items').append($variant);
    });
  }

  showMore(show) {
    this.$.moreResults.find('.loader').show().find('span').hide();
    this.$.moreResults.find('a').hide();
    this.$.moreResults.toggle(show);
    this.$.help.toggle(!show);
  }

  bindFiltersActions() {
    const params = this.filters.params;
    const $stars = this.$.params.find('.stars');

    this.$.search.find('.sidebar .content').stick_in_parent({
      offset_top: 60
    });

    const self = this;

    $stars.find('a').on('click', function starsClick() {
      const $el = $(this);
      const html = $el.html();
      const star = $el.data('stars');
      $stars.find('button .text').html(html);

      self.formObject.data.filters.stars = star;
      self.formObject.$.form.find('.search-button button').click();

      return false;
    });

    const $meals = this.$.params.find('.meals');

    $meals.find('a').on('click', function mealsClick() {
      const $el = $(this);
      const html = $el.html();
      const meal = $el.data('meal');
      $meals.find('button .text').html(html).find('small').hide();

      self.formObject.data.filters.meal = meal;
      self.formObject.$.form.find('.search-button button').click();

      return false;
    });


    this.$.filters.find('#types input').on('change', (e) => {
      const $el = $(e.target);
      const checked = $el.is(':checked');
      const type = $el.val();

      if (checked) {
        this.$.items.find(`.item[data-${type}="1"]:not(.template)`).removeClass('hiddenTypes');
      } else {
        this.$.items.find(`.item[data-${type}="1"]:not(.template)`).addClass('hiddenTypes');
      }
    });

    this.$.filters.find('#filters-hotel').on('keyup', (e) => {
      const $el = $(e.target);
      const query = $el.val();

      params.name = query;

      if (query.length > 0) {
        this.$.items.find('.item:not(.template)').addClass('hiddenName');

        this.$.items.find(`.item[data-name*="${query.toLowerCase()}"]:not(.template)`).removeClass('hiddenName');
      } else {
        this.$.items.find('.item:not(.template)').removeClass('hiddenName');
      }

      if (this.$.items.find('.item:not(.hiddenName)').length === 0) {
        this.$.noResults.show();
      } else {
        this.$.noResults.hide();
      }

      this.checkVisible();

      return false;
    });

    this.$.priceSlider.ionRangeSlider({
      type: 'double',
      grid: true,
      min: 0,
      max: 0,
      from: 0,
      to: 0,
      postfix: ' р.',
      hide_min_max: true,
      onFinish: (data) => {
        this.filterPrice(data.from, data.to);
      },
    });

    this.priceSlider = this.$.priceSlider.data('ionRangeSlider');
  }

  filterPrice(from, to) {
    this.$.items.find('.item:not(.template) .variant').addClass('hiddenPrice').each((i, variant) => {
      const price = parseInt($(variant).attr('data-price'), 10);

      if (price >= from && price <= to) { $(variant).removeClass('hiddenPrice'); }
    });
  }

  filterParamsChanged() {
    if (this.current === 'finished') {
      this.showFinished();
    }
  }

  rebuildFilters() {
    this.priceSlider.update({
      min: this.filters.params.minPrice,
      max: this.filters.params.maxPrice,
      from: this.filters.params.minPrice,
      to: this.filters.params.maxPrice,
    });
  }
}
