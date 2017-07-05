import $ from 'jquery';
import moment from 'moment';
import 'jquery.scrollto';

import BranchesMap from '../common/branches-map.es6';
import { IS_DEV } from '../../app.es6';
import Humanize from '../utils/humanize.es6';
import SearchForm from '../components/search-form.es6';

export default class MainPage {

  constructor() {
    this.map = new BranchesMap();
    this.map.init();

    this.form = new SearchForm();
    this.form.init();


    this.$ = {
      hot: $('#hot'),
      offices: $('.offices'),
      mobilePromoLink: $('#mobile-promo-link'),
      mobilePromo: $('#mobile-promo'),
      map: $('.block.map'),
    };

    this.hotCity = $('#searchForm').data('departure');
    this.hotUrl = this.$.hot.data('url');
  }

  init() {
    this.initHot();
    this.initScroll();
  }

  initScroll() {
    this.$.offices.on('click', () => {
      $(window).scrollTo(this.$.map, 300, {
        offset: -80,
      });

      return false;
    });

    this.$.mobilePromoLink.on('click', () => {
      $(window).scrollTo(this.$.mobilePromo, 300, {
        offset: -80,
      });

      return false;
    });
  }

  initHot() {
    $.getJSON(`${this.hotUrl}?items=8&departure=${this.hotCity}`, (response) => {
      if (IS_DEV) {
        console.log('[HOT TOURS] Response:', response);
      }
      const $tourTemplate = this.$.hot.find('.hotel.template');
      const $items = this.$.hot.find('.items');

      const tours = (response.length !== 0) ? response : [];

      $.each(tours, (i, tour) => {
        const $tour = $tourTemplate.clone();
        $tour.removeClass('template');

        const discount = parseInt((100 * tour.price) / tour.priceold, 10);

        $tour.find('a').attr('href', `/tour/${tour.tourid}`);

        $tour.find('.image .bg').css('background-image', `url(${tour.hotelpicture.replace('small', 'medium')})`);
        $tour.find('.image .discount').text(`-${discount}%`);

        $tour.find('.about .title').text(`${tour.hotelstars}* ${tour.hotelname.toLowerCase()}`);
        $tour.find('.about .where .country').text(tour.countryname);
        $tour.find('.about .where .region').text(tour.hotelregionname);

        const date = moment(tour.flydate, 'DD.MM.YYYY');

        $tour.find('.about .info .length .date').text(date.format('D MMMM'));
        $tour.find('.about .info .length .nights').text(Humanize.nights(tour.nights));

        $tour.find('.about .info .price span').text(Humanize.price(tour.price));

        if (i < 8) $items.append($tour);
      });

      if (tours.length > 0) { this.$.hot.find('.loader').hide(); }
    });
  }
}
