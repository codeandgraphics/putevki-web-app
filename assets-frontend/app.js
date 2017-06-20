import $ from 'jquery';
import 'bootstrap-webpack';

import './less/main.less';
import Maps from './js/common/branches-map';

window.cityMap = null;
window.branchesMap = null;

$(document).ready(() => {
  const maps = new Maps();
  maps.init();
});
