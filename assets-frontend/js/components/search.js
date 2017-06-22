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
    this.hotelLink = '/hotel/';
    this.requestId = requestId;
    this.status = 'searching';
    this.noImage = '/assets/img/no-image.png';

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
    $.getJSON(`${this.endpoint}status/${this.requestId}`, (data) => {
      this.processStatus(data.status);
    });
  }

  getResults(limit, callback) {
    $.getJSON(`${this.endpoint}results/${this.requestId}`, {
      limit,
    }, (data) => {
      callback(data);
    });
  }

  processStatus(status) {
    this.status = status.state;

    this.setProgress(status);

    if (this.status === 'searching') {
      if (status.toursfound > 0 && !this.first.shown) {
        this.first.hotelsTotal = status.hotelsfound;
        this.first.toursTotal = status.toursfound;
        this.first.minPrice = status.minprice;

        if (IS_DEV) console.log(`[ПОИСК] Нашел туры, получаем первые результаты. Прошло с начала: ${new Date() - self.startDate}мс`);

        this.getResults(this.first.hotelsTotal, (data) => {
          if (IS_DEV) console.log(`[ПОИСК] Получили первые результаты, ${this.first.hotelsTotal}${Humanize.hotelsFound(status.hotelsfound)}. Прошло с начала: ${new Date() - this.startDate}мс`);

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
      if (status.toursfound === 0) {
        if (IS_DEV) console.log('[ПОИСК] Туры не найдены');

        this.notFound();
      } else {
        if (IS_DEV) console.log(`[ПОИСК] Поиск на сервере завершен, получаем результаты. Прошло с начала: ${new Date() - this.startDate}мс`);

        this.all.hotelsTotal = parseInt(status.hotelsfound, 10);
        this.all.toursTotal = parseInt(status.toursfound, 10);
        this.all.minPrice = parseInt(status.minprice, 10);

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
      if (isScrolledIntoView(this.$.moreResults.find('a')) && !this.all.loading) {
        this.showNext();
      }
    }
  }

  getFinishedPage(page) {
    $.getJSON(`${this.endpoint}results/${this.requestId}`, {
      page,
    }, (data) => {
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

    this.$.search.find('.progressbar .percent .count').text(status.hotelsfound);
    this.$.search.find('.progressbar .percent .text').text(Humanize.hotelsFound(status.hotelsfound));
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

    console.log(hotels);
    $.each(hotels, (i, hotel) => {
      if (hotel.tours.length > 0) {
        const $item = this.buildHotel(hotel);
        this.populateTours($item, hotel.tours);
        this.$.items.append($item);
      }
    });

    this.$.moreResults.find('.loader').hide();
    this.$.moreResults.find('a').show();

    this.checkVisible();

    if (IS_DEV) console.log(`[ПОИСК] Завершили рендер результатов за ${new Date() - renderTime}мс. Прошло с начала: ${new Date() - this.startDate}мс`);

    this.rebuildFilters();
  }

  buildHotel(hotel) {
    const $item = this.$.template.clone();
    $item.removeClass('template');

    $item.attr('data-hotel', hotel.id);
    $item.attr('data-name', hotel.name.toLowerCase());
    $item.attr('data-stars', hotel.stars);

    $item.find('.place span').text(`${hotel.country.name}, ${hotel.region.name}`);

    if (hotel.types) {
      $item.attr('data-active', hotel.types.active);
      $item.attr('data-relax', hotel.types.relax);
      $item.attr('data-family', hotel.types.family);
      $item.attr('data-health', hotel.types.health);
      $item.attr('data-city', hotel.types.city);
      $item.attr('data-beach', hotel.types.beach);
      $item.attr('data-deluxe', hotel.types.deluxe);

      const $types = $item.find('.types');

      if (hotel.types.active) $types.append('<li class="type">Активный</li>');
      if (hotel.types.relax) $types.append('<li class="type">Спокойный</li>');
      if (hotel.types.family) $types.append('<li class="type">Семейный</li>');
      if (hotel.types.health) $types.append('<li class="type">Лечебный</li>');
      if (hotel.types.city) $types.append('<li class="type">Городской</li>');
      if (hotel.types.beach) $types.append('<li class="type">Пляжный</li>');
      if (hotel.types.deluxe) $types.append('<li class="type deluxe">Эксклюзивный</li>');
    }

    $item.find('.title a').text(hotel.name.toLowerCase()).attr('href', hotel.hotelLink);

    if (!hotel.image) { hotel.image = this.noImage; }

    $item.find('.image .bg').css('background-image', `url(${hotel.image})`);
    $item.find('.image a').attr('href', hotel.hotelLink);
    $item.find('.about .description').text(hotel.description);

    const $stars = $item.find('.stars');
    const stars = parseInt(hotel.stars, 10);

    for (let s = 0; s < 5; s += 1) {
      $stars.append((s < stars) ? '<i class="star ion-ios-star"></i>' : '<i class="no-star ion-ios-star-outline"></i>');
    }

    if (hotel.hotelrating !== 0) {
      $item.find('.review strong').text(hotel.rating);
      $item.find('.review span').text(Humanize.rating(hotel.rating));
    } else {
      $item.find('.review').hide();
    }

    return $item;
  }

  populateTours($item, tours) {
    $item.find('.variants .variant').not('.template').remove();

    $item.find('.more').removeClass('hidden').off('click').on('click', (e) => {
      const $el = $(e.target);
      $el.addClass('hidden');
      $item.find('.variants .variant').not('.template').show(100);
      return false;
    });

    $item.find('.other .variants-open').off('click').on('click', (e) => {
      const $el = $(e.target);
      $el.siblings('.variants-close').show();
      $el.hide();
      $item.find('.variants').show(100);
      return false;
    });

    $item.find('.other .variants-close').off('click').on('click', (e) => {
      const $el = $(e.target);
      $el.siblings('.variants-open').show();
      $el.hide();
      $item.find('.variants').hide();
      return false;
    });

    if (tours.length <= 5) $item.find('.more').addClass('hidden');

    $.each(tours, (i, tour) => {
      if (i !== 0) {
        const $variant = this.$.variant.clone();

        $variant.removeClass('template');
        if (i > 4) $variant.hide();

        $variant.attr('data-price', tour.price);

        $variant.find('.price a').text(`${Humanize.price(tour.price)} р.`).attr('href', this.tourlink + tour.tourid);

        const dateTo = moment(tour.flydate, 'DD.MM.YYYY');
        $variant.find('.date span').text(dateTo.format('D MMMM'));
        $variant.find('.date small').text(Humanize.nights(tour.nights));

        $variant.find('.room span').text(tour.room);

        $variant.find('.meal span').text(tour.mealrussian);

        $variant.find('.operator .icon img').attr('src', $variant.find('.operator .icon img').data('src').replace('{id}', tour.operatorcode)).attr('alt', tour.operatorname);
        $variant.find('.operator span').text(tour.operatorname);

        $item.find('.variants .items').append($variant);
      }
    });

    // Min price
    const tour = tours[0];

    $item.find('.sum .order').text(`${Humanize.price(tour.price)} р.`).attr('href', this.tourlink + tour.tourid);

    if (tour.price < this.filters.params.minPrice) {
      this.filters.params.minPrice = parseInt(tour.price, 10);
    }
    if (tour.price > this.filters.params.maxPrice) {
      this.filters.params.maxPrice = parseInt(tour.price, 10);
    }

    $item.attr('data-price', tour.price);

    const dateTo = moment(tour.flydate, 'DD.MM.YYYY');
    $item.find('.icons .date span').text(dateTo.format('D MMMM'));
    $item.find('.icons .date small').text(Humanize.nights(tour.nights));
    $item.find('.icons .room span').text(tour.room);
    $item.find('.icons .meal span').text(tour.mealrussian);

    $item.find('.icons .operator img').attr('src', $item.find('.icons .operator img').data('src').replace('{id}', tour.operatorcode)).attr('alt', tour.operatorname);
    $item.find('.icons .operator span').text(tour.operatorname);
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
      offset_top: 80,
    });

    $stars.find('a').on('click', (e) => {
      const $el = $(e.target);
      const html = $el.html();
      const star = $el.data('stars');
      $stars.find('button .text').html(html);

      this.formObject.data.filters.stars = star;
      this.formObject.$.form.find('.search-button button').click();

      return false;
    });

    const $meals = this.$.params.find('.meals');

    $meals.find('a').on('click', (e) => {
      const $el = $(e.target);
      const html = $el.html();
      const meal = $el.data('meal');
      $meals.find('button .text').html(html).find('small').hide();

      this.formObject.data.filters.meal = meal;
      this.formObject.$.form.find('.search-button button').click();

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
    this.$.items.find('.item:not(.template)').addClass('hiddenPrice').each((i, item) => {
      const price = parseInt($(item).attr('data-price'), 10);

      if (price >= from && price <= to) { $(item).removeClass('hiddenPrice'); }
    });
  }

  filterParamsChanged() {
    if (this.current === 'finished') {
      // self.finished.filtered = self.filterHotels(self.finished.data);
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
