import $ from 'jquery';
import 'bootstrap-webpack';
import moment from 'moment';
import 'moment/locale/ru';

import './less/main.less';

import Modals from './js/components/modals.es6';
import { initActions, mobileOverlay } from './js/common/actions.es6';

import MainPage from './js/pages/main.es6';
import SearchPage from './js/pages/search.es6';
import TourPage from './js/pages/tour.es6';
import HotelPage from './js/pages/hotel.es6';
import CountryPage from './js/pages/country.es6';

export const IS_DEV = global.env === 'development';
export const DATE_FORMAT = 'DD.MM.YYYY';
export const DATE_VISIBLE_FORMAT = 'D MMM';

$(document).ready(() => {
  moment.locale('ru');

  $('[data-toggle="tooltip"]').tooltip();

  let page = null;
  $.get('/api/citiesAndCountries', citiesAndCountries => {
    switch (global.route) {
      case 'main':
        page = new MainPage(citiesAndCountries);
        break;
      case 'search':
        page = new SearchPage();
        break;
      case 'tour':
        page = new TourPage();
        break;
      case 'hotel':
        page = new HotelPage();
        break;
      case 'country':
        page = new CountryPage();
        break;
      default:
        break;
    }

    initActions();

    mobileOverlay();

    const modals = new Modals();
    modals.init();

    if (page) page.init();

    if (IS_DEV) console.log(`Current route: ${global.route}`);
  });
});