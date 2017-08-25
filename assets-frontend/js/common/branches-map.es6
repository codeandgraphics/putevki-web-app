import $ from 'jquery';
import pin from '../../img/pin.png';

export default class BranchesMap {

  constructor() {
    this.map = null;
    this.ymaps = global.ymaps;

    this.city = global.currentCity;
    this.branches = global.branches;
  }

  init() {
    this.ymaps.ready(() => this.createMap());
  }

  createMap() {
    this.map = new this.ymaps.Map('mainMap', {
      center: [parseFloat(this.city.lat), parseFloat(this.city.lon)],
      zoom: parseInt(this.city.zoom, 10),
      controls: ['zoomControl'],
    });

    this.map.behaviors.disable('scrollZoom');
    this.map.behaviors.disable('drag');

    this.addCities();
  }

  addCities() {
    global.cities.forEach((city) => {
      this.map.geoObjects.add(
        new global.ymaps.Placemark([parseFloat(city.lat), parseFloat(city.lon)], {
          balloonContentHeader: `Путёвки из ${city.name_rod}`,
          hintContent: city.name,
        }, {
          iconLayout: 'default#image',
          iconImageHref: pin,
          iconImageSize: [36, 44],
          iconImageOffset: [-18, -44],
        }),
      );
      this.map.setBounds(this.map.geoObjects.getBounds());
      this.map.setZoom(this.map.getZoom() - 0.4);
    });

    $('.block.map .loader').hide();
  }
}
