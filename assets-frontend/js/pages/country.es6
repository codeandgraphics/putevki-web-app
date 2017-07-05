import $ from 'jquery';
import moment from 'moment';

import SearchForm from '../components/search-form.es6';
import Humanize from '../utils/humanize.es6';

import { IS_DEV } from '../../app.es6';

export default class CountryPage {

  init() {
    this.$ = {
      hot: $('#hot'),
    };

    this.url = this.$.hot.data('url');
    this.country = this.$.hot.data('country');
    this.region = this.$.hot.data('region');
    this.departure = this.$.hot.data('departure');

    this.form = new SearchForm();
    this.form.init();

    this.initHot();
  }

  initHot() {
    $.getJSON(
      `${this.url}?items=6&departure=${this.departure}&country=${this.country}&region=${this.region}`,
      (response) => {
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
