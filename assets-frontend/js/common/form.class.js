'use strict';
// FORM ------------------------------------------------------------

function Form(){
	
	this.$el = $('#searchForm');
	this.$nights = this.$el.find('.popup-nights');
	this.$people = this.$el.find('.popup-people');
	
	this.$from = this.$el.find('.from');

	this.popularCountries = this.$el.data('countries').split(',');
	this.popularRegions = this.$el.data('regions').split(',');
	
	this.endpoint = '/ajax/';
	this.debug = (env === 'development');
	
	this.limits = {
		nights: {
			min: 3,
			max: 29
		},
		people: {
			min: 1,
			max: 5,
			kids: 3
		}
	};
	
	this.data = {};
	
	this.initData();
	
	this.bindActions();
	
}

Form.prototype.initData = function(){
	var self = this;
	
	self.data.departure = self.$el.data('departure');
	
	self.data.country = self.$el.data('country');
	self.data.region = self.$el.data('region');
	self.data.hotel = self.$el.data('hotel');
	
	self.data.date = self.$el.data('date');
	self.data.date_range = self.$el.data('date-range');
	
	self.data.nights = self.$el.data('nights');
	self.data.nights_range = self.$el.data('nights-range');
	
	self.data.adults = self.$el.data('adults');
	self.data.kids = (typeof self.$el.data('kids') === 'number') ?
		[self.$el.data('kids')] :
		$.map(self.$el.data('kids').split('+'), function(value){ return parseInt(value, 10); });

	if(self.data.kids[0] === 0) {
		self.data.kids = [];
	}
	
	self.data.stars = self.$el.data('stars');
	self.data.meal = self.$el.data('meal');

	self.data.hotel = self.$el.data('hotel');

	self.data.operator = self.$el.data('operator');
};

Form.prototype.bindActions = function(){
	var form = this;

	form.fromActions();
	form.whereActions();
	form.nightsActions();
	form.peopleActions();
	
	form.dateActions();
	
	form.submitActions();
	
	$(document).mouseup(function (e)
	{
		var container = $(".popup");
		
		if (!container.is(e.target)
		    && container.has(e.target).length === 0)
		{
		    container.addClass('hidden');
		}
	});
};

Form.prototype.getValue = function(key, def){
	
	var value = this.data[key];
	
	if(value === 'true'){
		value = true;
	}else if(value === 'false'){
		value = false;
	}
	
	if(key === 'date_range' || key === 'nights_range'){
		value = (value == 1);
	}

	if(key === 'kids'){
		if(value === 0){
			value = [];
		}else if(typeof value === 'string'){
			value = value.split(',');
		}
	}
	
	if(key === 'adults'){
		value = parseInt(value, 10);
	}
	
	return value;
};

Form.prototype.setValue = function(key, value){
	var form = this;
	form.data[key] = value;
};

Form.prototype.setText = function(type,value){
	var form = this;
	
	if(type === 'nights'){
		var nightsText = Humanize('nights',value);
		form.$nights.find('.value').text(nightsText);
		form.$nights.find('.popup .selector .param').text(nightsText);
		
	}
	
	if(type === 'adults'){	
		var adultsText = Humanize('adults',value);
		form.$people.find('.popup .selector .param').text(adultsText);
	}
	
	if(type === 'people'){
		var peopleText = Humanize('people',value);
		form.$people.find('.value').text(peopleText);
	}
};

Form.prototype.fromActions = function(){
	var self = this;

	var $fromText = self.$from.find('.from-text');

	self.$from.find('select').on('change', function(){
		var id = $(this).val();
		var gen = $(this).find(':selected').attr('data-gen');

		console.log(id, gen);

		var isVisible = (id != 99);
		$fromText.toggle(isVisible);

		self.setValue('departure', id);
		self.$from.find('#fromDropdown span').text(gen);

		if(self.$from.hasClass('search')) {
			self.$el.find('.search-button button').click();
		}
	});
	
};

Form.prototype.whereActions = function(){
	
	var self = this;
	var $where = self.$el.find('.where');
	var $whereInput = $where.find('input');
	var $close = $('<a href="#" class="close"><i class="ion-ios-close-empty"></i></a>');
	
	$where.append($close);
	
	$close.on('click',function(){
		$whereInput.typeahead('val','').focus();
		self.setValue('country', false);
		self.setValue('region', false);
		$close.hide();
		return false;
	});
	
	$.getJSON(self.endpoint + 'destinations/', function(data){
		
		var countriesList = [];
		var regionsList = [];
		
		$.each(data.countries, function(i, country){
			country.isCountry = true;
			countriesList[country.id] = country.name;
		});
		
		$.each(data.regions, function(i, region){
			region.isRegion = true;
			regionsList[region.id] = region;
		});
		
		var countries = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function(obj) { return obj.name; },
			local: data.countries
		});
		
		var regions = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name', 'country_name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function(obj) { return obj.name; },
			local: data.regions
		});

		var hotels = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: self.endpoint + 'hotels/?query=%QUERY',
				wildcard: '%QUERY',
				filter: function(hotels){
					return $.map(hotels, function (hotel) {
						return {
							id: hotel.id,
							name: hotel.name,
							country: hotel.country,
							region: hotel.region,
							regionName: hotel.regionName,
							isHotel: true
						};
					});
				}
			}
		});
		
		function countriesDefault(q, sync) {
			if (q === '') {
				sync(countries.get(self.popularCountries));
			}else{
				countries.search(q, sync);
			}
		}
		
		function regionsDefault(q, sync) {
			if (q === '') {
				sync(regions.get(self.popularRegions));
			}else{
				regions.search(q, sync);
			}
		}

		$whereInput.typeahead({
			hint: true,
			highlight: true,
			minLength: 0
		}, {
			name: 'countries',
			source: countriesDefault,
			display: 'name',
			displayKey: 'id',
			templates: {
				header: '<h3>Страны</h3>',
				suggestion: function(item){
					return '<div><span>'+item.name+'</span></div>';
				}
			}
		}, {
			name: 'regions',
			source: regionsDefault,
			display: 'name',
			displayKey: 'id',
			limit: 7,
			templates: {
				header: '<h3>Регионы</h3><div class="suggestions">',
				footer: '</div>',
				suggestion: function(item){
					return '<div><span>'+item.name+'</span> <i> '+countriesList[item.country]+'</i></div>';
				}
			}
		}, {
			name: 'hotels',
			source: hotels,
			display: 'name',
			templates: {
				header: '<h3>Отели</h3><div class="suggestions">',
				footer: '</div>',
				suggestion: function(item){
					return '<div><span>'+item.name.toLowerCase()+'</span><i> '+item.regionName+'</i></div>';
				}
			}
		}).on('typeahead:autocomplete typeahead:select',function(e,object){
			var hotelId = false;
			var regionId = false;
			var countryId = false;

			var where = false;

			if(object.isCountry)
			{
				countryId = object.id;
				where = object.name;
			}

			if(object.isRegion)
			{
				regionId = object.id;
				countryId = object.country;
				where = countriesList[countryId] + '(' + regionsList[regionId].name + ')';
			}

			if(object.isHotel)
			{
				hotelId = object.id;
				regionId = object.region;
				countryId = object.country;
				where = countriesList[countryId] + '(' + regionsList[regionId].name + ')';
			}

			$where.removeClass('error');
			self.setValue('country', countryId);
			self.setValue('region', regionId);
			self.setValue('hotel', hotelId);
			self.setValue('where', where);
			$close.show();

			return false;

		}).on('typeahead:change',function(e, value){

			$where.removeClass('error');

			if(
				countries.get(value).length == 0 &&
				regions.get(value).length == 0 &&
				!self.getValue('hotel')
			){
				self.setValue('country', false);
				self.setValue('region', false);
				self.setValue('hotel', false);
				self.setValue('where', false);
				$whereInput.typeahead('val','');
				$close.hide();
			}
		});

		var country = self.getValue('country');
		var region = self.getValue('region');
		var hotel = self.getValue('hotel');
		var where = countriesList[country];

		if(regionsList[region]){
			where += '(' + regionsList[region].name + ')';
			$whereInput.typeahead('val',regionsList[region].name);
			$close.show();
		}else if(country){
			$whereInput.typeahead('val',countriesList[country]);
			$close.show();
		}
		
		self.setValue('where', where);
		
		self.formReady();
	});
};

Form.prototype.nightsActions = function(){
	var form = this;
	var limits = form.limits.nights;
	var nights = form.getValue('nights');
	var range = form.getValue('nights_range');
	
	var $popup = form.$nights.find('.popup');
	var $selector = $popup.find('.selector');
	var $range = $popup.find('.range-checkbox input');
	
	form.$nights.find('.range').toggle(range);
	$range.prop('checked',range);
	form.setText('nights', nights);
	
	if(nights >= limits.max){
		$selector.find('.plus').addClass('disabled');
	}else{
		$selector.find('.plus').removeClass('disabled');
	}
	
	if(nights <= limits.min){
		$selector.find('.minus').addClass('disabled');
	}else{
		$selector.find('.minus').removeClass('disabled');
	}
	
	form.$nights.find('.value, .range').click(function(){
		$popup.removeClass("hidden");
		$selector.find('.minus').off('click').on('click',function(){
			if(!$(this).hasClass('disabled')){
				nights--;
				form.setText('nights', nights);
				form.setValue('nights', nights);
			}
			if(nights <= limits.min){
				$(this).addClass('disabled');
			}else{
				$(this).removeClass('disabled');
			}
			if(nights < limits.max) $selector.find('.plus').removeClass('disabled');
			return false;
		});
		
		$selector.find('.plus').off('click').on('click',function(){
			if(!$(this).hasClass('disabled')){
				nights++;
				form.setText('nights', nights);
				form.setValue('nights', nights);
			}
			if(nights >= limits.max){
				$(this).addClass('disabled');
			}else{
				$(this).removeClass('disabled');
			}
			if(nights > limits.min)	$selector.find('.minus').removeClass('disabled');
			return false;
		});
		
		$range.off('change').on('change',function(){
			if($range.is(':checked')){
				form.setValue('nights_range', true);
				form.$nights.find('.range').show();
			}else{
				form.setValue('nights_range', false);
				form.$nights.find('.range').hide();
			}
		});
		
		
		return false;
	});
	
};

Form.prototype.peopleActions = function(){
	
	var form = this;
	var limits = form.limits.people;
	var adults = form.getValue('adults');
	var kids = form.getValue('kids');
	var people = adults;
	if(kids.length) people += kids.length;
	
	var $popup = form.$people.find('.popup');
	var $adultsSelector = $popup.find('.selector');
	var $kidsSelect = $popup.find('select');
	var $kidsAlert = $popup.find('.info');
	var $kidTemplate = $popup.find('.kid.template');

	var kidDelete = function(){
		kids.splice( $.inArray(parseInt($(this).parent().data('age'), 10), kids), 1 );
		people--;
		form.setValue('kids',kids);
		form.setText('people',people);
		$(this).parent().remove();
		if(kids.length >= limits.kids){
			$kidsSelect.hide();
			$kidsAlert.show();
		}else{
			$kidsSelect.show();
			$kidsAlert.hide();
		}
		console.log(kids);
		return false;
	}
	
	form.setText('adults',adults);
	form.setText('people',people);	
		
	for(var i = 0; i < kids.length;i++){
		
		var $kid = form.createKid($kidTemplate, Humanize('age',kids[i]), kids[i], kidDelete);
		
		form.$people.find('.kids').append($kid);
	}
	
	if(adults >= limits.max){
		$adultsSelector.find('.plus').addClass('disabled');
	}else{
		$adultsSelector.find('.plus').removeClass('disabled');
	}
	
	if(adults <= limits.min){
		$adultsSelector.find('.minus').addClass('disabled');
	}else{
		$adultsSelector.find('.minus').removeClass('disabled');
	}
		
	if(kids.length >= limits.kids){
		$kidsSelect.hide();
		$kidsAlert.show();
	}else{
		$kidsSelect.show();
		$kidsAlert.hide();
	}
	
	form.$people.find('.value').click(function(){
		
		$popup.removeClass("hidden");
		
		$adultsSelector.find('.minus').off('click').on('click',function(){
			
			if(!$(this).hasClass('disabled')){
				adults--;
				people--;
				
				form.setValue('adults',adults);
				form.setText('adults',adults);
				form.setText('people',people);
			}
			
			if(adults <= limits.min){
				$(this).addClass('disabled');
			}else{
				$(this).removeClass('disabled');
			}
			
			if(adults < limits.max){
				$adultsSelector.find('.plus').removeClass('disabled');
			}
			
			return false;
		});
		
		$adultsSelector.find('.plus').off('click').on('click',function(){
			
			if(!$(this).hasClass('disabled')){
				adults++;
				people++;
				
				form.setValue('adults',adults);
				form.setText('adults',adults);
				form.setText('people',people);
				
			}
				
			if(adults >= limits.max){
				$(this).addClass('disabled');
			}else{
				$(this).removeClass('disabled');
			}
			
			if(adults > limits.min){
				$adultsSelector.find('.minus').removeClass('disabled');
			}
			
			
			return false;
		});
		
		$kidsSelect.off('change').on('change',function(){
			
			var age = parseInt($(this).val(), 10);
			
			var $kid = form.createKid($kidTemplate, $kidsSelect.find('option:selected').text(), age, kidDelete);
			
			form.$people.find('.kids').append($kid);

			kids.push(age);
			people++;
			
			form.setValue('kids',kids);
			form.setText('people',people);
			
			if(kids.length >= limits.kids){
				$kidsSelect.hide();
				$kidsAlert.show();
			}else{
				$kidsSelect.show();
				$kidsAlert.hide();
			}
			
			$(this).val('');
			return false;
		});
		
		return false;
	});
};

Form.prototype.createKid = function(template, text, age, callback){
	
	var $kid = template.clone();
	$kid.removeClass('template');
	$kid.find('span').text(text);
	$kid.attr('data-age',age);
	
	$kid.find('i').off('click').on('click', callback);
	
	return $kid;
};

Form.prototype.dateActions = function(){
	var form = this;
	
	var minDate = new Date;
	minDate.addDays(1);
	
	var currentDate = moment(form.getValue('date'),'DD.MM.YYYY');
	
	form.$el.find('.when').pickmeup_twitter_bootstrap({
		default_date: currentDate.toDate(),
		date: currentDate.toDate(),
		min: minDate,
		format: 'e b',
		select_month: false,
		select_year: false,
		locale: Locale,
		change: function(date){
			var dateVal = $(this).pickmeup('get_date');
			$(this).find('.value').text(date);
			form.setValue('date', moment(dateVal).format('DD.MM.YYYY'));
		}
	}).find('.value').text(currentDate.format('D') + ' ' + Locale.monthsShort[currentDate.format('M') - 1]);
	
	$('.pickmeup-twitter-bootstrap').append('<div class="range-checkbox"><input type="checkbox" id="date-range-days" value="1" name="date-range-days"'+ ((form.getValue('date_range')) ? 'checked="checked"' : '') + '> <label for="date-range-days">± 2 дня</label></div>');
	
	if(form.getValue('date_range')) form.$el.find('.when .range').show();
	
	$('.pickmeup-twitter-bootstrap .range-checkbox label').off('click').on('click',function(){
		
		var $input = $(this).siblings('input[type="checkbox"]');
		
		if($input.is(':checked')){
			form.$el.find('.when .range').hide();
			$input.prop('checked',false);
			form.setValue('date_range', false);
		}else{
			form.$el.find('.when .range').show();
			$input.prop('checked',true);
			form.setValue('date_range', true);
		}
	});
	
};

Form.prototype.submitActions = function(){
	var self = this;
	
	self.$el.on('submit',function(){
		return false;
	});
	
	self.$el.find('.search-button button').on('click',function(){
		
		$(this).prop('disabled',true);
		self.$el.find('.loader').show();
		
		if(self.formCheck()){	
			
			var data = {
				from		: self.data.departure,
				where		: self.data.where,
				adults		: self.data.adults,
				kids		: (self.data.kids) ? self.data.kids.join('+') : 0,
				stars		: self.data.stars,
				meal		: self.data.meal
			};

			if(self.data.hotel)
			{
				data.hotel = self.data.hotel;
			}
			
			data.date = (self.data['date_range']) ? '~' + self.data.date : self.data.date;
			data.nights = (self.data['nights_range']) ? '~' + self.data.nights : self.data.nights;

			$.getJSON(self.endpoint+'search/',{
				params	: data
			},function(res){
				if(res.url){
					window.location.href = res.url;
				}
			});
		}
		
		return false;
	});
};

Form.prototype.formCheck = function(){
	var self = this;
	
	var errors = [];
	
	if(!self.data.region && !self.data.country){
		errors.push('where');
	}
	
	if(errors.length > 0){
		
		$.each(errors, function(i, error){
			self.$el.find('.'+error).addClass('error');
		});
		
		self.$el.find('.search-button button').prop('disabled',false);
		self.$el.find('.loader').hide();
		
		return false;			
	}else{
		return true;
	}
};

Form.prototype.formReady = function(){
	var self = this;
	
	self.$el.find('.loader').hide();
	
	if(self.debug) console.log('[ФОРМА ПОИСКА] Форма загружена', new Date);
	
};

Form.prototype.buildQuery = function(){
	var ret = [];
	for (var d in data)
		ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
	return ret.join("&");
};

// FORM ------------------------------------------------------------
