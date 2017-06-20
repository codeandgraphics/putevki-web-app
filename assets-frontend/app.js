import $ from 'jquery';
import 'bootstrap-webpack';
import moment from 'moment';
import 'moment/locale/ru';

import MainPage from './js/pages/main';

import './less/main.less';

$(document).ready(() => {

  moment.locale('ru');

  const mainPage = new MainPage();
  mainPage.init();

});

export const IS_DEV = global.env === 'development';
export const DATE_FORMAT = 'DD.MM.YYYY';
export const DATE_VISIBLE_FORMAT = 'D MMM';

export default {};