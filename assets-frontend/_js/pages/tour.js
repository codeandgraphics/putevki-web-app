
let tour;
$(document).ready(() => {
  const q = getQuery();

	 tour = new Tour(q.tour);

  console.log(tour);

  $(() => {
    $('[data-toggle="tooltip"]').tooltip();
  });
});

const setOffice = function (id) {
  tour.branch = id;
};

ymaps.ready(initOfficesMap);

function initOfficesMap() {
  const mainMap = new ymaps.Map('officesMap', {
    center: [parseFloat(currentCity.lat), parseFloat(currentCity.lon)],
    zoom: parseInt(currentCity.zoom),
    controls: ['zoomControl'],
  });

  mainMap.behaviors.disable('scrollZoom');

  $.each(branches, (i, branch) => {
    let branchText = '';
    if (branch.phone) branchText += `Телефон: ${branch.phone}<br/>`;
    if (branch.site) branchText += `Сайт: <a href="${branch.site}">${branch.site}</a><br/>`;
    if (branch.email) branchText += `E-mail: <a href="mailto:${branch.email}">${branch.email}</a><br/>`;
    if (branch.timetable) branchText += `Время работы: ${branch.timetable}<br/>`;

    branchText += `<button type="submit" class="btn btn-block btn-primary" onclick="setOffice(${branch.id});">Выбрать офис</button>`;

    let branchIcon = '/assets/img/pin.png';
    if (branch.main == 0) branchIcon = '/assets/img/pin-partner.png';

    mainMap.geoObjects.add(
			new ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
  balloonContentHeader: branch.name,
  balloonContentBody: branch.address,
  balloonContentFooter: branchText,
  hintContent: branch.name,
}, {
  iconLayout: 'default#image',
  iconImageHref: branchIcon,
  iconImageSize: [36, 44],
  iconImageOffset: [-18, -44],
}),
		);
  });

  $.each(cities, (i, city) => {
    const $city = $('<option/>');
    $city.val([city.lat, city.lon, city.zoom].join(';'));
    $city.text(city.name);

    if (city.id == currentCity.id) { $city.prop('selected', true); }


    $('.city-select select').append($city);
  });

  $('.city-select select').on('change', function () {
    const coords = $(this).val().split(';');

    const lat = parseFloat(coords[0]);
    const lon = parseFloat(coords[1]);
    const zoom = parseFloat(coords[2]);

    mainMap.setCenter([lat, lon], zoom, { duration: 0 });
  });

  $('.map .loader').hide();
}
