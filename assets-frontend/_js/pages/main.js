$(document).ready(function(){

	moment.locale('ru');

	var form = new Form();
	var hotCity = $('#searchForm').data('departure');

	$.getJSON("//tourvisor.ru/xml/hottours.php?format=json&items=8&city="+hotCity+"&callback=?", function(response){

		if(env == 'development')
		{
			console.log('[HOT TOURS] Response:', response);
		}
		var $tourTemplate = $('#hot').find('.hotel.template');
		var $items = $('#hot').find('.items');

		var tours = (response.hottours != undefined && response.hottours.hotcount != 0) ? response.hottours.tour : [];

		$.each(tours, function(i, tour){

			var $tour = $tourTemplate.clone();
			$tour.removeClass('template');

			var discount = parseInt((100 * tour.price) / tour.priceold);

			$tour.find('a').attr('href','/tour/'+tour.tourid);

			$tour.find('.image .bg').css('background-image','url('+tour.hotelpicture.replace('small','medium')+')');
			$tour.find('.image .discount').text('-'+discount+'%');

			$tour.find('.about .title').text(tour.hotelstars+'* '+tour.hotelname.toLowerCase());
			$tour.find('.about .where .country').text(tour.countryname);
			$tour.find('.about .where .region').text(tour.hotelregionname);

			var date = moment(tour.flydate, "DD.MM.YYYY");

			$tour.find('.about .info .length .date').text(date.format('D MMMM'));
			$tour.find('.about .info .length .nights').text(Humanize('nights',tour.nights));

			$tour.find('.about .info .price span').text(Humanize('price',tour.price));

			if(i < 8) $items.append($tour);

		});

		if(tours.length > 0)
			$('#hot .loader').hide();
	});

	$(window).resize(function(){
		//onResize();
	});

	$('.offices').on('click', function(){

		$(window).scrollTo($('.block.map'), 300, {
			offset: -80
		});

		return false;
	});

	$('#mobile-promo-link').on('click', function(){

		$(window).scrollTo($('#mobile-promo'), 300, {
			offset: -80
		});

		return false;
	});

	//onResize();
});


function onResize(){

	var minWidth = 908;
	var wideWidth = 1200;

	var blocksInRow = ($(window).width() >= wideWidth) ? 4 : 3;

	var $hot = $('#hot .items .item:not(.template)');
	var $popular = $('#popular .items .item:not(.template)');

	var hotCount = $hot.length;
	var popularCount = $popular.length;

	var excludeHot = hotCount % blocksInRow;
	var excludePopular = popularCount % blocksInRow;

	if(excludeHot > 0){
		$hot.slice(-excludeHot).hide();
	}else{
		$hot.show();
	}
	if(excludePopular > 0){
		$popular.slice(-excludePopular).hide();
	}else{
		$popular.show();
	}

};

ymaps.ready(initMainMap);

function initMainMap(){

	var mainMap = new ymaps.Map('mainMap', {
		center: [parseFloat(currentCity.lat), parseFloat(currentCity.lon)],
		zoom: parseInt(currentCity.zoom),
		controls: ["zoomControl"]
	});

	mainMap.behaviors.disable('scrollZoom');

	$.each(branches, function(i, branch)
	{
		var branchText = '';
		if(branch.phone) branchText += "Телефон: " + branch.phone + '<br/>';
		if(branch.site) branchText += 'Сайт: <a href="' + branch.site + '">' + branch.site + '</a><br/>';
		if(branch.email) branchText += 'E-mail: <a href="mailto:' + branch.email + '">' + branch.email + '</a><br/>';
		if(branch.timetable) branchText += "Время работы: " + branch.timetable + '<br/>';

		var branchIcon = '/assets/img/pin.png';
		if(branch.main == 0) branchIcon = '/assets/img/pin-partner.png';

		mainMap.geoObjects.add(
			new ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
				balloonContentHeader: branch.name,
				balloonContentBody: branch.address,
				balloonContentFooter: branchText,
				hintContent: branch.name
			}, {
				iconLayout: 'default#image',
				iconImageHref: branchIcon,
				iconImageSize: [36, 44],
				iconImageOffset: [-18, -44]
			})
		);
	});

	$.each(cities, function(i, city)
	{
		var $city = $('<a href="#"/>');
		$city.data('lat', city.lat);
		$city.data('lon', city.lon);
		$city.data('zoom', city.zoom);
		$city.text(city.name);
		if(city.main == 1)
			$city.addClass('main-city');
		if(city.id == currentCity.id)
			$city.addClass('active');

		$city.on('click', function(){
			if(!$(this).hasClass('active'))
			{
				$('#mapCities a').removeClass('active');
				$(this).addClass('active');
				var lat = parseFloat($(this).data('lat'));
				var lon = parseFloat($(this).data('lon'));
				var zoom = parseFloat($(this).data('zoom'));
				mainMap.setCenter([ lat, lon ], zoom, { duration: 0 });
			}
			return false;
		});
		$('#mapCities').append($('<li/>').append($city));
	});

	$('.block.map .loader').hide();

}