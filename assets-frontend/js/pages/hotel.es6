import $ from 'jquery';
import 'jquery.scrollto';
import 'sticky-kit/dist/sticky-kit';
import 'lightslider';

import SearchForm from '../components/search-form.es6';
import HotelForm from '../components/hotel-form.es6';


export default class HotelPage {

  init() {
    /* $('.grid').masonry({
      itemSelector: '.grid-item',
      columnWidth: 414,
    });*/

    $('.light-slider').lightSlider({
      gallery: true,
      item: 1,
      loop: true,
      slideMargin: 0,
      thumbItem: 9,
      verticalHeight: 240,
    });

    $('#hotel .sidebar .content').stick_in_parent({
      offset_top: 80,
    });

    const form = new SearchForm();
    const hotelForm = new HotelForm(form);

    if (window.location.hash === '#tours') {
      $(window).scrollTo('#tours', 300, {
        offset: {
          top: -100,
        },
      });
    }

    form.$.form.find('.search-button button').off('click').on('click', () => {
      hotelForm.$.no.hide();
      hotelForm.$.loader.show();
      hotelForm.$.variants.html('');
      hotelForm.$.more.hide();

      form.data.from = parseInt(form.$.form.find('.from select').val(), 10);

      $.getJSON(`${form.endpoint}searchHotel/`, {
        params: form.data,
      }, (res) => {
        hotelForm.start(res.searchId);
      });
      return false;
    }).click();
  }
}
