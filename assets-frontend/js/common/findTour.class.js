var findTourForm;

$(document).ready(function(){
	findTourForm = new FindTour();
});

function FindTour()
{
	var self = this;

	self.$el = $('#findTourModal');

	self.departures = self.$el.data('departures').split(',');
	self.countries = self.$el.data('countries').split(',');
	self.regions = self.$el.data('regions').split(',');
	self.from = self.$el.data('from');
	self.fromId = self.$el.data('from-id');

	self.data = {};

	self.endpoint = '/ajax/';
	self.debug = (env == 'development');

	self.initControls();
}

FindTour.prototype.initControls = function()
{
	var self = this;

	self.$el.find('#find-phone').inputmask({"mask": "+9(999)999-99-99"});

	self.initDepartures();
	self.initWhere();
	self.initTypes();
	self.initFields();

	self.$el.find('#sendFind').on('click', function(){
		self.sendAction();
	});

	self.setValue('departure', self.fromId);
	self.setValue('from', self.from);
};

FindTour.prototype.sendAction = function()
{
	var self = this;

	var error = 0;

	if(!self.data.name){
		self.$el.find('#find-name').focus().parent().addClass('has-error');
		error++;
	}else{
		self.$el.find('#find-name').parent().removeClass('has-error');
	}
	if(!self.data.phone) {
		self.$el.find('#find-phone').focus().parent().addClass('has-error');
		error++;
	}else{
		self.$el.find('#find-phone').parent().removeClass('has-error');
	}
	if(!self.data.email)
	{
		self.$el.find('#find-email').focus().parent().addClass('has-error');
		error++;
	}else{
		self.$el.find('#find-email').parent().removeClass('has-error');
	}

	if(error == 0)
	{
		var type = 'find';

		self.$el.modal('hide');

		var dataString = JSON.stringify(self.data);

		$('#onlineStatusModal').modal({
			backdrop: 'static',
			keyboard: false
		}).addClass('loading').addClass(type);

		console.log(dataString);

		$.post('/ajax/findTour', {data: dataString, type: type }, function(response){

			setTimeout(function(){
				$('#onlineStatusModal').removeClass('loading').addClass('success');

				setTimeout(function(){
					$('#onlineStatusModal').modal('hide');
					$(this).prop('disabled',false).removeClass('disabled');
				}, 2000);
			}, 1500);

		}, 'json');
	}

	return false;
};

FindTour.prototype.initTypes = function()
{
	var self = this;

	self.$el.find('.types input').on('change', function(){

		var $item = $(this);
		self.setValue($item.val(), ($item.prop('checked')));

	});
};

FindTour.prototype.initFields = function()
{
	var self = this;

	self.$el.find('input[type=text]').on('change', function(){

		var $item = $(this);
		self.setValue($item.attr('id').replace('find-',''), $item.val());

	});
};

FindTour.prototype.initDepartures = function()
{
	var self = this;

	$.getJSON(self.endpoint + 'departures/', function(data){

		var departuresList = [];

		$.each(data.departures, function(i, departure){
			departuresList[departure.id] = departure.name;
		});

		var departures = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function(obj) { return obj.name; },
			local: data.departures
		});

		function departuresDefault(q, sync) {
			if (q === '') {
				sync(departures.get(self.departures));
			}else{
				departures.search(q, sync);
			}
		}

		self.$el.find('#find-departure').typeahead({
			hint: true,
			highlight: true,
			minLength: 0
		}, {
			name: 'departures',
			source: departuresDefault,
			display: 'name',
			displayKey: 'id',
			templates: {
				header: '<h3>Город</h3>',
				suggestion: function(item){
					return '<div>'+item.name+'</div>';
				}
			}
		}).on('typeahead:autocomplete typeahead:select',function(e, object){
			self.setValue('departure', object.id);
			self.setValue('from', departuresList[object.id]);
			return false;
		}).on('typeahead:change',function(e,value){
			if(departures.get(value).length == 0){
				self.setValue('departure', false);
				self.setValue('from', false);
				self.$el.find('#find-departure').typeahead('val','');
			}
		});
	});
};

FindTour.prototype.initWhere = function(){

	var self = this;

	$.getJSON(self.endpoint + 'destinations/', function(data){

		var countriesList = [];
		var regionsList = [];

		$.each(data.countries, function(i, country){
			countriesList[country.id] = country.name;
		});

		$.each(data.regions, function(i, region){
			regionsList[region.id] = region;
		});

		var countries = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function(obj) { return obj.name; },
			local: data.countries
		});

		var regions = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function(obj) { return obj.name; },
			local: data.regions
		});

		function countriesDefault(q, sync) {
			if (q === '') {
				sync(countries.get(self.countries));
			}else{
				countries.search(q, sync);
			}
		}

		function regionsDefault(q, sync) {
			if (q === '') {
				sync(regions.get(self.regions));
			}else{
				regions.search(q, sync);
			}
		}

		self.$el.find('.where input').typeahead({
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
					return '<div>'+item.name+'</div>';
				}
			}
		}, {
			name: 'regions',
			source: regionsDefault,
			display: 'name',
			displayKey: 'id',
			templates: {
				header: '<h3>Регионы</h3><div class="suggestions">',
				footer: '</div>',
				suggestion: function(item){
					return '<div>'+item.name+' <span class="country"> '+countriesList[item.country]+'</span></div>';
				}
			}
		}).on('typeahead:autocomplete typeahead:select',function(e,object){

			self.setValue('country', (object.country) ? object.country : object.id);
			self.setValue('region', (object.country) ? object.id : false);

			var where = object.name;
			if(object.country)
				where = countriesList[object.country] + '(' + object.name + ')';

			self.setValue('where', where);

			return false;
		}).on('typeahead:change',function(e,value){
			if(countries.get(value).length == 0){
				if(regions.get(value).length == 0){
					self.setValue('country', false);
					self.setValue('region', false);
					self.setValue('where', false);
					self.$el.find('.where input').typeahead('val','');
				}
			}
		});
	});
};

FindTour.prototype.setValue = function(key, value){
	var self = this;
	self.data[key] = value;

	if(value)
	{
		self.$el.find('#selected dt.text-' + key).removeClass('hide');
		self.$el.find('#selected dd.' + key).removeClass('hide');
	}
	else
	{
		self.$el.find('#selected dt.text-' + key).addClass('hide');
		self.$el.find('#selected dd.' + key).addClass('hide');
	}

	console.log(['beach', 'excursion', 'skiing'].indexOf(key));

	if(['beach', 'excursion', 'skiing'].indexOf(key) != -1)
	{
		if(self.data['beach'] || self.data['excursion'] || self.data['skiing'])
		{
			self.$el.find('#selected dt.text-types').removeClass('hide');
			self.$el.find('#selected dd.types').removeClass('hide');
		}
		else
		{
			self.$el.find('#selected dt.text-types').addClass('hide');
			self.$el.find('#selected dd.types').addClass('hide');
		}
		self.$el.find('#selected dd.types .' + key).toggleClass('hide', !value);
	}
	else
	{
		self.$el.find('#selected dd.' + key).text(value);
	}

	console.log(JSON.stringify(self.data), self.data);
};