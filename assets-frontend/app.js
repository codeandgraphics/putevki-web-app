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

export default {};