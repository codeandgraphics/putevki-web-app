import $ from 'jquery';
import 'jquery.scrollto';

import OfficesMap from '../common/offices-map';
import Tour from '../components/tour';
import { IS_DEV } from '../../app';

global.setOffice = function(id) {
  global.branch = id;
};

export default class TourPage {

  init() {
    this.tour = new Tour();

    if (IS_DEV) console.log(this.tour);

    $('[data-toggle="tooltip"]').tooltip();

    this.map = new OfficesMap();
    this.map.init();
  }
}
