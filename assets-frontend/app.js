import $ from 'jquery';
import 'bootstrap-webpack';
import moment from 'moment';
import 'moment/locale/ru';

import './less/main.less';

import MainPage from './js/pages/main';
import SearchPage from './js/pages/search';
import TourPage from './js/pages/tour';
import HotelPage from './js/pages/hotel';

export const IS_DEV = global.env === 'development';
export const DATE_FORMAT = 'DD.MM.YYYY';
export const DATE_VISIBLE_FORMAT = 'D MMM';

$(document).ready(() => {
  moment.locale('ru');

  let page = null;

  switch (global.route) {
    case 'main':
      page = new MainPage();
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
    default:
      break;
  }

  if(page) page.init();

  if (IS_DEV) console.log(`Current route: ${global.route}`);
});

export default {};
