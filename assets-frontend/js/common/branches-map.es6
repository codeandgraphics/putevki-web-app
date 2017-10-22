import $ from 'jquery';
import pin from '../../img/pin.png';

const buildHref = (city, country) => ` <a href="/search/${city.name}/${country.name}" target="_blank">${country.name}</a>`;

const renderBaloonBody = (city) => {
  if (city.countries) {
    return `Страны вылета: <br/>${city.countries.map(country => buildHref(city, country))}`;
  }
  return '';
};

export default class BranchesMap {

  constructor(citiesWithCountries) {
    this.map = null;
    this.ymaps = global.ymaps;

    this.city = global.currentCity;
    this.branches = global.branches;

    this.cities = citiesWithCountries;
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

    this.addCities();
  }

  addCities() {
    this.cities.forEach((city) => {
      this.map.geoObjects.add(
        new global.ymaps.Placemark([parseFloat(city.lat), parseFloat(city.lon)], {
          balloonContentHeader: `Путёвки из ${city.name_rod}`,
          balloonContentBody: renderBaloonBody(city),
          hintContent: `${city.name}`,
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
