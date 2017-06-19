function HotelForm(form)
{
	var self = this;

	self.form = form;
	self.tourvisorId = null;
	self.tourlink = '/tour/';

	self.$el = $('.tours .results');
	self.$variants = this.$el.find('.variants');
	self.$loader = this.$el.find('.loader');
	self.$no = this.$el.find('.no-results');
	self.$more = this.$el.find('.more');
	self.$template = this.$el.find('.variant.template');

	self.hasFirst = false;
	self.firstResults = [];
	self.allResults = [];
	self.isSearching = false;

	self.$no.hide();
	self.$loader.show();

	self.debug = (env == 'development');

	self.$more.find('a').on('click', function(){
		self.$variants.find('.variant').show(100);
		self.$more.hide();
		return false;
	});
}

HotelForm.prototype.start = function(tourvisorId)
{
	var self = this;
	self.tourvisorId = tourvisorId;
	self.allResults = [];
	self.firstResults = [];
	self.hasFirst = false;
	self.isSearching = true;
	self.getStatus();
};

HotelForm.prototype.getStatus = function()
{
	var self = this;

	if(self.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Получаем статус: ', new Date());

	$.getJSON(self.form.endpoint+'status/'+self.tourvisorId, function(res)
	{
		self.form.$el.find('.progressbar').css('width', res.status.progress + '%');
		if(res.status.state == 'finished')
		{
			self.isSearching = false;
			self.$loader.hide();

			self.getResults(false, function()
			{
				if(self.hasFirst)
				{
					self.$more.show();
				}
				else
				{
					self.showResults();
				}
			});
		}
		else
		{
			if(res.status.state == 'searching')
			{
				if(res.status.toursfound > 1 && !self.hasFirst)
				{
					self.getResults(true);
				}
			}

			setTimeout(function(){
				self.getStatus();
			}, 3000);
		}
		if(self.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Статус: ', res.status);
	});
};

HotelForm.prototype.getResults = function(first, callback)
{
	var self = this;

	if(self.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Получаем результаты: ', new Date());

	$.getJSON(self.form.endpoint+'results/'+self.tourvisorId, function(res){

		if(res.status.toursfound > 0)
		{
			if(first)
			{
				self.firstResults = res.hotels[0].tours;
				self.hasFirst = true;
				self.showResults();
			}
			else
			{
				self.allResults = res.hotels[0].tours;

				callback();

				if(self.allResults.length == self.firstResults.length)
				{
					self.$more.hide();
				}
			}
		}

		//self.$loader.hide();
		//self.form.$el.find('.progressbar').css('width', '0%');
	});
};

HotelForm.prototype.showResults = function()
{
	var self = this;

	self.$loader.hide();

	if(self.isSearching)
	{
		if(self.hasFirst)
		{
			self.buildResults(self.firstResults);
		}
	}
	else
	{
		if(self.allResults.length > 0)
		{
			self.buildResults(self.allResults);
			self.form.$el.find('.progressbar').css('width', '0%');
		}
		else
		{
			self.$no.show();
			self.form.$el.find('.progressbar').css('width', '0%');
		}
	}
};

HotelForm.prototype.buildResults = function(tours)
{
	var self = this;

	self.$variants.find('.variant:not(.template)').remove();

	$.each(tours, function(i, tour)
	{
		var $variant = self.$template.clone();

		$variant.removeClass('template');

		$variant.attr('data-price', tour.price);

		$variant.find('.price a').text(Humanize('price',tour.price) + ' р.').attr('href', self.tourlink + tour.tourid);

		var dateTo = moment(tour.flydate,'DD.MM.YYYY');
		$variant.find('.date span').text(dateTo.format('D MMMM'));
		$variant.find('.date small').text(Humanize('nights',tour.nights));

		$variant.find('.room span').text(tour.room);

		$variant.find('.meal span').text(tour.mealrussian);

		$variant.find('.operator .icon img').attr('src',$variant.find('.operator .icon img').data('src').replace('{id}',tour.operatorcode)).attr('alt',tour.operatorname);
		$variant.find('.operator span').text(tour.operatorname);

		self.$variants.append($variant);
	});

	if(self.debug) console.log('[ФОРМА ПОИСКА ОТЕЛЕЙ] Завершили рендер: ', new Date());
}