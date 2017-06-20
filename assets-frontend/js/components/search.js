import $ from 'jquery';
import { IS_DEV } from '../../app';
import { isScrolledIntoView } from '../utils/helpers';

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
    $.getJSON(`${this.endpoint}status/${this.request}`, (data) => {
      this.processStatus(data.status);
    });
  }

  getResults(limit, callback) {
    $.getJSON(`${this.endpoint}results/${self.request}`, {
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
          if (IS_DEV) console.log(`[ПОИСК] Получили первые результаты, ${this.first.hotelsTotal}${Humanize('hotelsText', status.hotelsfound)}. Прошло с начала: ${new Date() - this.startDate}мс`);

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
      if (isScrolledIntoView(this.$moreResults.find('a')) && !this.all.loading) {
        this.showNext();
      }
    }
  }
}
