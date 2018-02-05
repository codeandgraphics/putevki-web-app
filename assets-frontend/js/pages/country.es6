import $ from 'jquery';
import moment from 'moment';

import SearchForm from '../components/search-form.es6';
import Humanize from '../utils/humanize.es6';

import { IS_DEV } from '../../app.es6';

export default class CountryPage {
  init() {
    this.$ = {
      hotRegion: $('#hotRegion'),
      hotCountry: $('#hotCountry'),
    };

    this.hotRegionUrl = this.$.hotRegion.data('url');
    this.hotRegionCountry = this.$.hotRegion.data('country');
    this.hotRegion = this.$.hotRegion.data('region');
    this.hotRegionDeparture = this.$.hotRegion.data('departure');

    this.hotCountryUrl = this.$.hotCountry.data('url');
    this.hotCountry = this.$.hotCountry.data('country');
    this.hotCountryDeparture = this.$.hotCountry.data('departure');

    this.form = new SearchForm();
    this.form.init();

    if (this.hotRegionUrl) {
      this.initHotRegion();
    }

    if (this.hotCountryUrl) {
      this.initHotCountry();
    }
  }

  initHotCountry() {
    $.getJSON(
      `${this.hotCountryUrl}?items=9&departure=${this.hotCountryDeparture}&country=${this.hotCountry}`,
      (response) => {
        if (IS_DEV) {
          console.log('[HOT TOURS COUNTRY] Response:', response);
        }
        const $tourTemplate = this.$.hotCountry.find('.hotel.template');
        const $items = this.$.hotCountry.find('.items');

        const tours = (response !== null) ? response : [];

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

        this.$.hotRegion.find('.loader').hide();

        // TODO
        if (tours.length === 0) { console.log('empty'); }
      });
  }

  initHotRegion() {
    $.getJSON(
      `${this.hotRegionUrl}?items=6&departure=${this.hotRegionDeparture}&country=${this.hotRegionCountry}&region=${this.hotRegion}`,
      (response) => {
        if (IS_DEV) {
          console.log('[HOT TOURS REGION] Response:', response);
        }
        const $tourTemplate = this.$.hotRegion.find('.hotel.template');
        const $items = this.$.hotRegion.find('.items');

        const tours = (response !== null) ? response : [];

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

        this.$.hotRegion.find('.loader').hide();

        // TODO
        if (tours.length === 0) { console.log('empty'); }
      });
  }
}
