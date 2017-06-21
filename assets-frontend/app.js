import $ from 'jquery';
import 'bootstrap-webpack';
import moment from 'moment';
import 'moment/locale/ru';

import MainPage from './js/pages/main';

import './less/main.less';
import SearchPage from './js/pages/search';

export const IS_DEV = global.env === 'development';
export const DATE_FORMAT = 'DD.MM.YYYY';
export const DATE_VISIBLE_FORMAT = 'D MMM';

$(document).ready(() => {
  moment.locale('ru');

  switch (global.route) {
    case 'main':
      const mainPage = new MainPage();
      mainPage.init();
      break;
    case 'search':
      const searchPage = new SearchPage();
      searchPage.init();
      break;
    default:
      break;
  }

  if (IS_DEV) console.log(`Current route: ${global.route}`);
});

export default {};
