function Tour(){
	'use strict';
	var self = this;
	
	moment.locale('ru');
	
	self.endpoint = '/ajax/';
	self.hotelLink = '/hotel/';
	
	self.startDate = new Date;
	
	self.debug = true;
	
	self.tour = {};
	self.flight = null;
	self.tourists = 0;
	self.$forms = [];

	self.branch = false;
	
	self.price = {
		price: 0,
		fuel: 0,
		visa: 0,
		infant: 0,
		addprice: 0,
		base: 0
	};

	self.basePrice = 0;

	self.$el = $('#tour');
	self.$prices = self.$el.find('.checkout');

	self.$loader = self.$el.find('.actualize');
	self.$flights = self.$el.find('.flights');
	self.$tourists = self.$el.find('.tourists');
	self.$payVariants = self.$el.find('.pay-variants');
	self.$later = self.$tourists.find('#tourists-later');

	self.$forms['online'] = self.$el.find('#online-form');
	self.$forms['request'] = self.$el.find('#request-form');
	self.$forms['office'] = self.$el.find('#office-form');

	self.tourData = self.$el.data('tour');
	
	self.initTour();
	
	if(self.debug) console.log('[ТУР] Инициализирован. '+self.startDate);
}

Tour.prototype.initTour = function(done){
	var self =  this;

	self.tour.id = self.tourData.id;

	self.tour.fuel = (self.tourData.fuelcharge) ? self.tourData.fuelcharge : 0;
	self.tour.visa = (self.tourData.visacharge) ? self.tourData.visacharge : 0;

	self.tour.price = self.tourData.price - self.tour.fuel;

	self.setPrice('price',self.tour.price);
	
	self.setActions();

	if(self.tourData.departurecode == '99')
	{
		self.withoutFlight();
		self.$payVariants.removeClass('locked');
		self.$loader.addClass('hide');
	}
	else
	{
		self.loadFlights();
	}
};

Tour.prototype.loadFlights = function(){
	var self = this;

	var $variants = self.$flights.find('.variants');
	var $items = self.$flights.find('.items');
	var $includes = self.$el.find('.includes');

	$.getJSON(self.$flights.data('url'), function(data){

		self.$loader.addClass('hide');

		if(data.iserror)
		{
			self.cannotActualize();
		}
		else
		{
			self.$flights.removeClass('hide');

			if(data.flights.length == 0)
			{
				self.hasNoFlights();
			}
			else
			{
				self.basePrice = data.flights[0].price.value;
				var $firstFlight = self.buildFlight(data.flights[0]);

				if($firstFlight)
				{
					$firstFlight.click();
					$items.append($firstFlight);

					if(data.flights.length > 1)
					{
						var $moreItems = self.$flights.find('#more-flights');
						$variants.removeClass('hide');
						for(var i = 1; i < data.flights.length; i++)
						{
							var $flight = self.buildFlight(data.flights[i]);
							$moreItems.append($flight);
						}
					}
				}
				else
				{
					self.hasNoFlights();
				}
			}

			if(data.tourinfo.flags != null)
			{
				var flags = data.tourinfo.flags;
				for(var flag in flags)
				{
					if(flags.hasOwnProperty(flag))
					{
						if(flags[flag])
						{
							$includes.find('.' + flag).addClass('none');
						}
					}
				}
			}
		}

		self.$payVariants.removeClass('locked');
	});
};

Tour.prototype.buildFlight = function(flight){

	if(flight.forward.length < 1 || flight.backward.length < 1)
	{
		return false;
	}

	var self = this;
	var $flight = self.$flights.find('.flight.template').clone();
	$flight.removeClass('template');

	var directions = {
		'forward'	: flight.forward[0],
		'backward'	: flight.backward[0]
	};

	for(var dir in directions)
	{
		var $direction = $flight.find('.' + dir);
		var direction = directions[dir];

		$direction.prop('title', direction.company.name + ', рейс ' + direction.number + ', ' + direction.plane);
		$direction.tooltip();

		var date = moment(flight['date' + dir], 'DD.MM.YYYY');

		var $departure = $direction.find('.departure');
		var $arrival = $direction.find('.arrival');

		if(date.isValid())
		{
			$departure.find('.date').text(date.format('D MMMM'));
			$arrival.find('.date').text(date.format('D MMMM'));
		}
		else
		{
			$departure.find('.date').text('Уточняется');
			$arrival.find('.date').text('Уточняется');
		}

		var departureTime = moment(direction.departure.time, 'HH:mm');
		$departure.find('.time').text(departureTime.format('HH:mm'));
		$departure.find('.airport').text(direction.departure.port.id + ', ' + direction.departure.port.name);

		var arrivalTime = moment(direction.arrival.time, 'HH:mm');
		$arrival.find('.time').text((arrivalTime.isValid()) ? arrivalTime.format('HH:mm') : "??:??");
		$arrival.find('.airport').text(direction.arrival.port.id + ', ' + direction.arrival.port.name);
	}

	$flight.find('.charge').html(flight.fuelcharge.value + ' <span>руб.</span>');

	var $fuel = $flight.find('.fuel');
	if(self.basePrice < flight.price.value )
	{
		$fuel.find('.changed .more').removeClass('hide');
		$fuel.find('.data').addClass('is-changed');
	}
	if(self.basePrice > flight.price.value )
	{
		$fuel.find('.changed .less').removeClass('hide');
		$fuel.find('.data').addClass('is-changed');
	}

	$flight.off('click').on('click', function(){
		self.$flights.find('.flight').removeClass('active');
		$flight.addClass('active');

		self.setPrice('fuel', flight.fuelcharge.value);
		self.setPrice('price', flight.price.value);

		self.flight = flight;
	});

	return $flight;
};

Tour.prototype.cannotPay = function(){
	var self = this;
	self.$el.find('.no-online').removeClass('hide');
	self.$payVariants.find('.variant.online').addClass('disabled');
	self.$payVariants.find('.variant.request a').click();
};

Tour.prototype.cannotActualize = function(){
	var self = this;
	self.$el.find('.no-actualize').removeClass('hide');
	self.cannotPay();
};

Tour.prototype.withoutFlight = function(){
	var self = this;
	self.$flights.find('.no-flights').removeClass('hide');
};

Tour.prototype.hasNoFlights = function(){
	var self = this;
	self.$flights.find('.no-flights').removeClass('hide');
	self.cannotPay();
};

Tour.prototype.setActions = function(){
	var self =  this;
	
	$('input[data-inputmask], input[data-inputmask-regex]').inputmask();
	
	self.$el.find('.sidebar .content').stick_in_parent({
		offset_top: 80
	});

	$('#buy a').click(function (e) {
		if ($(this).parent().hasClass("disabled"))
		{
			e.preventDefault();
			return false;
		}
		e.preventDefault()
		$(this).tab('show')
	})

	self.$later.on('change', function(){

		var checked = $(this).prop('checked');

		if(checked)
		{
			self.$tourists.find('.items').collapse('hide').on('hidden.bs.collapse', function(){
				self.$tourists.find('.later').show();
			});
			self.$tourists.find('.items input').prop('required', false);
			self.$tourists.find('.items .has-error').removeClass('has-error');
		}
		else
		{
			self.$tourists.find('.later').hide();
			self.$tourists.find('.items').collapse('show');
			self.$tourists.find('.items input').prop('required', true);
		}

	});
	
	self.$forms['online'].on('submit', function(){
		
		self.checkForm('online');

		return false;
	});

	self.$forms['office'].on('submit', function(){

		self.checkForm('office');

		return false;
	});

	self.$forms['request'].on('submit', function(){

		self.checkForm('request');

		return false;
	});
	
	self.$flights.find('.variants').on('click',function(){

		$(this).hide();
		$('#more-flights').collapse('show');

		return false;
	});
	
	self.$flights.find('.flight').off('click').on('click',function(){
		
		if($(this).not('.active')){

			self.$flights.find('.flight').removeClass('active');
			$(this).addClass('active');

			var fuel = parseInt($(this).data('fuel'));
			var price = parseInt($(this).data('price')) - fuel;
			
			self.setPrice('fuel', fuel);
			self.setPrice('price', price);

			self.flight = $(this).data('flight-id');
		}
		
		return false;
	});
	
	self.$tourists.find('.tourist .visa input').on('change', function()
	{
		var checked = $(this).is(':checked');
		self.setPrice('visa', checked ? 1 : -1);
	});
};

Tour.prototype.checkForm = function(type)
{
	var self = this;

	var $form = self.$forms[type];

	$form.validator('validate');

	if($form.find('#confirmation').prop('checked'))
	{
		$form.find('#confirmation').parent().removeClass('has-error');
	}
	else
	{
		$form.find('#confirmation').parent().addClass('has-error');
	}

	if($form.find('.form-group.has-error').length > 0)
	{
		var $input =  $form.find('.form-group.has-error').first().find('input');
		$input.focus();
		$(window).scrollTo($input, 300, {
			offset: -120
		});
	}
	else
	{
		$form.find('button[type=submit]').prop('disabled',true).addClass('disabled');

		var formData = $form.serializeObject();

		formData.price = self.tourData.price;
		formData.tour = self.tourData;
		formData.flight = self.flight;

		if(type == 'online')
		{
			self.sendOnline(formData, type);
		}

		if(type == 'request')
		{
			self.sendOnline(formData, type);
		}

		if(type == 'office')
		{
			formData.branch = self.branch;
			self.sendOnline(formData, type);
		}

		console.log(formData);
	}
};

Tour.prototype.sendOnline = function(data, type)
{
	var self = this;

	$('#onlineStatusModal').modal({
		backdrop: 'static',
		keyboard: false
	}).addClass('loading').addClass(type);

	$.post('/ajax/formOnline', {data: JSON.stringify(data), type: type }, function(response){

		setTimeout(function(){
			if(response.res == '')
			{
				$('#onlineStatusModal').removeClass('loading').addClass('error');

				setTimeout(function(){
					$('#onlineStatusModal').modal('hide');
					$(this).prop('disabled',false).removeClass('disabled');
				}, 2000);
			}
			else
			{
				$('#onlineStatusModal').removeClass('loading error').addClass('success');

				setTimeout(function(){
					$('#onlineStatusModal').modal('hide');
					window.location.href = response.res;
				}, 2000);
			}

		}, 1500);

	}, 'json');
};


Tour.prototype.setPrice = function(type, price)
{
	var self = this;
	
	price = parseInt(price);
	
	if(type == 'visa')
	{
		self.price.visa += price;
		
		if(self.price.visa > 0 && self.tour.visa > 0)
		{
			self.$prices.find('.tour-visa').removeClass('hidden');
			self.$prices.find('dd.tour-visa').text('+ ' + Humanize('price', self.price.visa * parseInt(self.tour.visa)) + ' руб.');
		}
		else
		{
			self.$prices.find('.tour-visa').addClass('hidden');
		}
	}
	else
	{
		self.price[type] = price;
		
		if(type == 'price')
		{
			self.$prices.find('dd.tour-price').text(Humanize('price', self.price[type]) + ' руб.');
		}
		else
		{
			if(self.price[type] >= 0)
			{
				self.$prices.find('.tour-'+type).removeClass('hidden');
				self.$prices.find('dd.tour-'+type).text('+ ' + Humanize('price', self.price[type]) + ' руб.');
			}
		}		
	}
	var sum = self.price.price + self.price.fuel;

	sum += self.price.visa * self.tour.visa;
	
	if(type == 'price')
		self.$el.find('.data input[name="price"]').val(price);
	
	self.$prices.find('.tour-sum strong').text(Humanize('price', sum) + ' руб.');
};

Tour.prototype.removeVisa = function()
{
	var self = this;
	
	
};


