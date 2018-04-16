import $ from 'jquery';
import pin from '../../img/pin.png';

const buildHref = (city, country) => {
  if (!country) return '';
  return ` <a href="/search/${city.name}/${country.name}" target="_blank">${country.name}</a>`;
};

const renderBaloonBody = (city) => {
  if (city.countries && city.name) {
    return `Страны вылета: <br/>${city.countries.map(country => buildHref(city, country))}`;
  }
  return '';
};

export default class BranchesMap {

  constructor(citiesWithCountries) {
    this.map = null;
    this.ymaps = global.ymaps;

    this.city = global.currentCity;

    this.cities = citiesWithCountries;
    console.log(this.city)
  }

  init() {
    // this.ymaps.ready(() => this.createMap());
  }

  createMap() {
    $('.block.map .loader').hide();
    this.map = new this.ymaps.Map('mainMap', {
      center: [parseFloat(this.city.lat), parseFloat(this.city.lon)],
      zoom: parseInt(this.city.zoom, 5),
      controls: ['zoomControl'],
    });

    // this.map.behaviors.disable('scrollZoom');

    // this.addCities();
  }

  addCities() {
    this.cities.forEach((city) => {
      this.map.geoObjects.add(
        new global.ymaps.Placemark([parseFloat(city.lat), parseFloat(city.lon)], {
          balloonContentHeader: `Путевки из ${city.nameRod}`,
          balloonContentBody: renderBaloonBody(city),
          hintContent: `${city.name}`,
        }, {
          iconLayout: 'default#image',
          iconImageHref: pin,
          iconImageSize: [36, 44],
          iconImageOffset: [-18, -44],
        }),
      );
    });

    // this.map.setBounds(this.map.geoObjects.getBounds());
  }
}
