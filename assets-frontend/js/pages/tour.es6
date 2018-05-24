import 'jquery.scrollto';

import OfficesMap from '../common/offices-map.es6';
import Tour from '../components/tour.es6';
import { IS_DEV } from '../../app.es6';

global.setOffice = function(id) {
  global.branch = id;
};

export default class TourPage {
  constructor({ branches, cities }) {
    this.branches = branches;
    this.cities = cities;
  }

  init() {
    this.tour = new Tour();

    this.map = new OfficesMap(this.cities, this.branches);
    this.map.init();
  }
}
