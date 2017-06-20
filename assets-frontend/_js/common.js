//ymaps.ready(initMaps);
var cityMap;
var branchesMap;

var isMobile = /iPhone|iPod|Android/.test(navigator.userAgent) && !window.MSStream;

function initMaps(){
    var cityBranches = $("#cityMap").data('branches');
	var city = $("#cityMap").data('city');
	
    cityMap = new ymaps.Map("cityMap", {
        center: [parseFloat(city.lat), parseFloat(city.lon)],
        zoom: parseInt(city.zoom),
        controls: ["zoomControl"]
    });
    cityMap.behaviors.disable('scrollZoom');
    
    $.each(cityBranches, function(i, branch){
	    var branchText = '';
	    if(branch.phone) branchText += "Телефон: " + branch.phone + '<br/>';
	    if(branch.site) branchText += 'Сайт: <a href="' + branch.site + '">' + branch.site + '</a><br/>';
	    if(branch.email) branchText += 'E-mail: <a href="mailto:' + branch.email + '">' + branch.email + '</a><br/>';
	    if(branch.timetable) branchText += "Время работы: " + branch.timetable + '<br/>';

		var branchIcon = '/assets/img/pin.png';
		if(branch.main == 0) branchIcon = '/assets/img/pin-partner.png';
	    
	    cityMap.geoObjects.add( new ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
				balloonContentHeader: branch.name,
				balloonContentBody: branch.address,
				balloonContentFooter: branchText,
				hintContent: branch.name
	    	}, {
		        iconLayout: 'default#image',
		        iconImageHref: branchIcon,
		        iconImageSize: [36, 44],
		        iconImageOffset: [-18, -44]
		    }) );
    });
    
    branchesMap = new ymaps.Map("branchesMap", {
        center: [parseFloat(city.lat), parseFloat(city.lon)],
        zoom: parseInt(city.zoom),
        controls: ["zoomControl"]
    });
    branchesMap.behaviors.disable('scrollZoom');
	branchesMap.behaviors.disable('drag');
    
    $.each(cityBranches, function(i, branch){
	    var branchText = '';
	    if(branch.phone) branchText += "Телефон: " + branch.phone + '<br/>';
	    if(branch.site) branchText += 'Сайт: <a href="' + branch.site + '">' + branch.site + '</a><br/>';
	    if(branch.email) branchText += 'E-mail: <a href="mailto:' + branch.email + '">' + branch.email + '</a><br/>';
	    if(branch.timetable) branchText += "Время работы: " + branch.timetable + '<br/>';

		//branchText += '<button class="btn btn-primary">Выбрать</button>';

		var branchIcon = '/assets/img/pin.png';
		if(branch.main == 0) branchIcon = '/assets/img/pin-partner.png';

	    branchesMap.geoObjects.add( new ymaps.Placemark([parseFloat(branch.lat), parseFloat(branch.lon)], {
				balloonContentHeader: branch.name,
				balloonContentBody: branch.address,
				balloonContentFooter: branchText,
				hintContent: branch.name
	    	}, {
		        iconLayout: 'default#image',
		        iconImageHref: branchIcon,
		        iconImageSize: [36, 44],
		        iconImageOffset: [-18, -44]
		    }) );
    });
}

$(document).ready(function(){

	if(isMobile && Cookies.get('mobile-overlay') !== 'closed') {
		$('body').addClass('disable-scroll');
		$('#mobile-overlay').removeClass('hidden').find('.close-overlay').on('click', function(){
			$('meta[name=viewport]').prop('content', 'width=1230');
			$('#mobile-overlay').addClass('hidden');
			$('body').removeClass('disable-scroll');
			Cookies.set('mobile-overlay', 'closed', { expires: 30 });
			return false;
		});
		$('meta[name=viewport]').prop('content', 'initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no');


	}
	
	$('#callBackModal form').on('submit',function(){
		var $el = $(this);
		
		$el.validator('validate');
		
		if($el.find('.form-group.has-error').length > 0)
		{
			var $input =  $el.find('.form-group.has-error').first().find('input');
			$input.focus();
			$(window).scrollTo($input, 300, {
				offset: -120
			});
		}
		else
		{		
			var formData = $el.serializeObject();
			
			$(this).prop('disabled',true).addClass('disabled');
		
			$('#callBackModal').addClass('loading');
			
			$.post('/ajax/tourHelp', {data: formData}, function(response){
				if(response.status == 'ok')
				{
					$('#callBackModal').removeClass('loading').addClass('success');
				}
				else
				{
					$('#callBackModal').removeClass('loading').addClass('error');
					$(this).prop('disabled',false).removeClass('disabled');
				}
				
			}, 'json');
		}

		
		return false;
	});
	
	
	// Scroll To Top
	
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('#upButton');

	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('is-visible') : $back_to_top.removeClass('is-visible fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('fade-out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

	
	
	
	/*$("#searchForm .where input").keypress(function(event){
		
		var keyCodes = { 126 : 1025, 70 : 1040, 60 : 1041, 68 : 1042, 85 : 1043, 76 : 1044, 84 : 1045, 58 : 1046, 80 : 1047, 66 : 1048, 81 : 1049, 82 : 1050, 75 : 1051, 86 : 1052, 89 : 1053, 74 : 1054, 71 : 1055, 72 : 1056, 67 : 1057, 78 : 1058, 69 : 1059, 65 : 1060, 123 : 1061, 87 : 1062, 88 : 1063, 73 : 1064, 79 : 1065, 125 : 1066, 83 : 1067, 77 : 1068, 34 : 1069, 62 : 1070, 90 : 1071, 102 : 1072, 44 : 1073, 100 : 1074, 117 : 1075, 108 : 1076, 116 : 1077, 59 : 1078, 112 : 1079, 98 : 1080, 113 : 1081, 114 : 1082, 107 : 1083, 118 : 1084, 121 : 1085, 106 : 1086, 103 : 1087, 104 : 1088, 99 : 1089, 110 : 1090, 101 : 1091, 97 : 1092, 91 : 1093, 119 : 1094, 120 : 1095, 105 : 1096, 111 : 1097, 93 : 1098, 115 : 1099, 109 : 1100, 39 : 1101, 46 : 1102, 122 : 1103, 96 : 1105, 92: 1105, 124: 1025}
		
		if(event.keyCode < 1000){
			$(this).val($(this).val() + String.fromCharCode(keyCodes[event.keyCode]));
			
			$(this).typeahead('val', $(this).val());
			
			return false;
		}
	    
	});*/
});
