import $ from 'jquery';
import 'masonry-layout';
import 'jquery.scrollto';
import 'sticky-kit/dist/sticky-kit';
import 'fotorama/fotorama';

import SearchForm from '../components/search-form';
import HotelForm from '../components/hotel-form';


export default class HotelPage {

  init() {
    /* $('.grid').masonry({
      itemSelector: '.grid-item',
      columnWidth: 414,
    });*/

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
