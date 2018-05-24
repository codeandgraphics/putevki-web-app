import $ from 'jquery';
import pin from '../../img/pin.png';
import partnerPin from '../../img/pin-partner.png';

export default class OfficesMap {

  constructor(cities, branches) {
    this.map = null;
    this.ymaps = global.ymaps;
    this.$mapCities = $('#officesMap');

    this.city = global.currentCity;
    this.branches = branches;
    this.cities = cities;
  }

  init() {
    this.ymaps.ready(() => this.createMap());
  }

  createMap() {
    this.map = new this.ymaps.Map('officesMap', {
      center: [parseFloat(this.city.lat), parseFloat(this.city.lon)],
      zoom: parseInt(this.city.zoom, 10),
      controls: ['zoomControl'],
    });

    this.map.behaviors.disable('scrollZoom');

    this.addBranches();
    this.addCities();
  }

  addBranches() {
    this.branches.forEach((branch) => {
      let branchText = '';
      if (branch.phone) branchText += `Телефон: ${branch.phone}<br/>`;
      if (branch.site) branchText += `Сайт: <a href="${branch.site}">${branch.site}</a><br/>`;
      if (branch.email) branchText += `E-mail: <a href="mailto:${branch.email}">${branch.email}</a><br/>`;
      if (branch.timetable) branchText += `Время работы: ${branch.timetable}<br/>`;

      branchText += `<button type="submit" class="btn btn-block btn-primary" onclick="setOffice(${branch.id});">Выбрать офис</button>`;

      this.map.geoObjects.add(
        new this.ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
          balloonContentHeader: branch.name,
          balloonContentBody: branch.address,
          balloonContentFooter: branchText,
          hintContent: branch.name,
        }, {
          iconLayout: 'default#image',
          iconImageHref: (branch.main !== '0') ? pin : partnerPin,
          iconImageSize: [36, 44],
          iconImageOffset: [-18, -44],
        }),
      );
    });
  }

  addCities() {
    this.cities.forEach((city) => {
      const $city = $('<option/>');
      $city.val([city.lat, city.lon, city.zoom].join(';'));
      $city.text(city.name);

      if (city.id === this.city.id) { $city.prop('selected', true); }


      $('.city-select select').append($city);
    });

    $('.city-select select').on('change', (e) => {
      const $el = $(e.target);
      const coords = $el.val().split(';');

      const lat = parseFloat(coords[0]);
      const lon = parseFloat(coords[1]);
      const zoom = parseFloat(coords[2]);

      this.map.setCenter([lat, lon], zoom, { duration: 0 });
    });

    $('.map .loader').hide();
  }
}
