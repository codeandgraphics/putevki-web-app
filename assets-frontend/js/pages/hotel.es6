import $ from 'jquery';
import 'jquery.scrollto';
import 'sticky-kit/dist/sticky-kit';
import 'lightslider';
import Masonry from 'masonry-layout';

import SearchForm from '../components/search-form.es6';
import HotelForm from '../components/hotel-form.es6';


export default class HotelPage {

  init() {
    this.masonry = new Masonry('.services', {
      itemSelector: '.grid-item',
    });

    $('.light-slider').lightSlider({
      gallery: true,
      item: 1,
      loop: true,
      slideMargin: 0,
      thumbItem: 9,
      verticalHeight: 240,
    });

    this.form = new SearchForm();
    this.form.init();
    const hotelForm = new HotelForm(this.form);

    if (window.location.hash === '#tours') {
      $(window).scrollTo('#tours', 300, {
        offset: {
          top: -100,
        },
      });
    }

    this.form.$.form.find('.search-button button').off('click').on('click', () => {
      hotelForm.$.no.hide();
      hotelForm.$.loader.show();
      hotelForm.$.variants.html('');
      hotelForm.$.more.hide();

      this.form.data.from = parseInt(this.form.$.form.find('.from select').val(), 10);

      $.getJSON(`${this.form.endpoint}searchHotel/`, {
        params: this.form.data,
      }, (res) => {
        hotelForm.start(res.searchId);
      });
      return false;
    }).click();
  }
}
