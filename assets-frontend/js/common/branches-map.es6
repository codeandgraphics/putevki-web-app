import $ from 'jquery';
import pin from '../../img/pin.png';
import partnerPin from '../../img/pin-partner.png';

export default class BranchesMap {

  constructor() {
    this.map = null;
    this.ymaps = global.ymaps;
    this.$mapCities = $('#mapCities');

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

      this.map.geoObjects.add(
        new global.ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
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
    global.cities.forEach((city) => {
      const $city = $('<a href="#" class="internal"/>');
      $city.data('lat', city.lat);
      $city.data('lon', city.lon);
      $city.data('zoom', city.zoom);
      $city.html(`<span>${city.name}</span>`);
      if (parseInt(city.main, 10) === 1) { $city.addClass('main-city'); }
      if (city.id === this.city.id) { $city.addClass('active'); }

      const self = this;

      $city.on('click', function() {
        const $el = $(this);
        if (!$el.hasClass('active')) {
          self.$mapCities.find('a').removeClass('active');
          $el.addClass('active');
          const lat = parseFloat($el.data('lat'));
          const lon = parseFloat($el.data('lon'));
          const zoom = parseFloat($el.data('zoom'));
          self.map.setCenter([lat, lon], zoom, { duration: 0 });
        }
        return false;
      });
      this.$mapCities.append($('<li/>').append($city));
    });

    $('.block.map .loader').hide();
  }
}
