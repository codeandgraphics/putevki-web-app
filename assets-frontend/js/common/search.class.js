function Search(request, form){
	'use strict';
	var self = this;
	
	moment.locale('ru');
	
	self.endpoint = '/ajax/';
	self.tourlink = '/tour/';
	self.hotelLink = '/hotel/';
	self.request = request;
	self.status = 'searching';
	self.noImage = '/assets/img/no-image.png';
	
	self.form = form;
	
	self.limit = 25;
	self.shown = 0;
	
	self.startDate = new Date;
	
	self.firstDelay = 1000;
	self.smallDelay = 3000;
	self.bigDelay = 4000;

	self.debug = (env == 'development');
	
	self.current = 'first';
	
	self.first = {
		hotels: [],
		hotelsTotal: 0,
		toursTotal: 0,
		minPrice: 0,
		shown: false
	};
	
	self.all = {
		hotels: [],
		hotelsTotal: 0,
		hotelsLoaded: 0,
		page: 1,
		toursTotal: 0,
		minPrice: 0,
		loading: true,
		done: false,
		shown: false
	};
	
	self.filters = {
		params: {
			stars: [],
			meals: [],
			maxPrice: 0,
			minPrice: 9999999,
			name: ''
		}
	};
	
	self.$el = $('#search');
	self.$filters = self.$el.find('.filters');
	self.$filtersOverlay = self.$filters.find('.overlay');
	self.$params = self.$el.find('.params');
	self.$template = self.$el.find('.item.template');
	self.$list = self.$el.find('.tours.list');
	self.$items = self.$list.children('.items');
	self.$variant = self.$template.find('.variants .variant.template');
	self.$loader = self.$el.find('.progressbar .loader');
	
	self.$process = self.$el.find('.search-process');
	
	self.$moreResults = self.$el.find('.more-results');
	self.$noResults = self.$el.find('.no-results');
	self.$help = self.$el.find('.help');
	
	self.$priceSlider = self.$filters.find('#price');
	
	if(self.request)
	{
		setTimeout(function(){
			self.getStatus();
		}, self.firstDelay);
			
		self.$loader.show();
		
		if(self.debug) console.log('[ПОИСК] Запускаем поиск', self.startDate);
	}
	else
	{
		self.notFound();
		if(self.debug) console.log('[ПОИСК] Нет данных для поиска', self.startDate);
	}
			
	$(window).on('scroll',function()
	{
		self.checkVisible();
	});
	
	self.$moreResults.find('a').on('click',function()
	{
		self.showNext();
		return false;
	});
	
	self.$el.find('.show-finished').off('click').on('click',function()
	{
		self.showFinished();
		return false;
	});
	
	self.bindFiltersActions();
}

Search.prototype.getStatus = function(){
	var self = this;
	
	$.getJSON(this.endpoint + 'status/'+self.request,function(data)
	{
		self.processStatus(data.status);
	});
};

Search.prototype.processStatus = function(status)
{
	var self = this;
	
	self.status = status.state;
	
	self.setProgress(status);
	
	if(self.status == 'searching')
	{
		if(status.toursfound > 0 && !self.first.shown)
		{
			self.first.hotelsTotal = status.hotelsfound;
			self.first.toursTotal = status.toursfound;
			self.first.minPrice = status.minprice;
			
			if(self.debug) console.log('[ПОИСК] Нашел туры, получаем первые результаты. Прошло с начала: '+(new Date - self.startDate)+'мс');
			
			self.getResults('first', self.first.hotelsTotal, function(data)
			{
				if(self.debug) console.log('[ПОИСК] Получили первые результаты, ' + self.first.hotelsTotal + Humanize('hotelsText',status.hotelsfound)+'. Прошло с начала: '+(new Date - self.startDate)+'мс');
				
				self.first.hotels = data.hotels;
				self.renderFirst();
				
			});
		}

		var delay = (self.first.shown) ? self.bigDelay : self.smallDelay;
		
		setTimeout(function()
		{
			if(self.debug) console.log('[ПОИСК] Обновляем статус, задержка '+delay+'. Прошло с начала: '+(new Date - self.startDate)+'мс');
			self.getStatus();
		}, delay);
	}
	
	if(self.status == 'finished')
	{
		if(status.toursfound == 0)
		{
			if(self.debug) console.log('[ПОИСК] Туры не найдены');
			
			self.notFound();
			
		}
		else
		{
			if(self.debug) console.log('[ПОИСК] Поиск на сервере завершен, получаем результаты. Прошло с начала: '+(new Date - self.startDate)+'мс');
			
			self.all.hotelsTotal = parseInt(status.hotelsfound);
			self.all.toursTotal = parseInt(status.toursfound);
			self.all.minPrice = parseInt(status.minprice);
			
			self.getResults('finished', self.limit, function(data)
			{
				if(self.debug) console.log('[ПОИСК] Получили финальные результаты. Прошло с начала: '+(new Date - self.startDate)+'мс');
				
				self.$loader.hide();
				
				self.all.hotels = data.hotels;
				self.all.hotelsLoaded += data.hotels.length;
				self.all.done = (self.all.hotelsLoaded >= self.all.hotelsTotal);
				
				self.stopSearch();
				
				self.all.loading = false;
			});
			
		}
	}
	
};

Search.prototype.getResults = function(type, limit, callback){
	var self = this;
	
	$.getJSON(this.endpoint + 'results/' + self.request, {
		limit: limit
	},function(data){
		callback(data);
	});
}

Search.prototype.checkVisible = function(){
	var self = this;
	
	if(self.all.shown && !self.all.done)
	{
		if(isScrolledIntoView(self.$moreResults.find('a')) && !self.all.loading)
		{
			self.showNext();
		}
	}
};

Search.prototype.getFinishedPage = function(page){
	var self = this;
	
	$.getJSON(this.endpoint + 'results/' + self.request, {
		page: page
	}, function(data)
	{
		if(data.hotels)
		{
			self.all.hotels = data.hotels;
			self.all.hotelsLoaded += data.hotels.length;
			self.all.done = (self.all.hotelsLoaded >= self.all.hotelsTotal);
				
			self.renderFinished();
		}
			
		self.all.loading = false;
	});
}

Search.prototype.showNext = function(){
	var self = this;
	self.all.loading = true;
	self.$moreResults.find('a').hide().siblings('.loader').show();
	self.all.page++;
	self.getFinishedPage(self.all.page);
	if(self.debug) console.log('[ПОИСК] Показываем следующую страницу');
};


Search.prototype.setProgress = function(status){
	var self = this;
	
	self.$el.find('.progressbar .bar').animate({
		width: status.progress+'%'
	},300);
	
	self.$el.find('.progressbar .percent .count').text(status.hotelsfound);
	self.$el.find('.progressbar .percent .text').text(Humanize('hotelsText',status.hotelsfound));
};


Search.prototype.stopSearch = function(){
	var self = this;
	
	
	if(self.first.shown)
	{
		var priceDiff = self.first.minPrice - self.all.minPrice;
		var toursDiff = self.all.toursTotal - self.first.toursTotal;
		
		self.$process.find('.tours-found').text(Humanize('tours',toursDiff));
		
		if(priceDiff > 0)
		{
			self.$process
				.find('.cheaper-found')
					.show()
					.find('.price-found')
						.text(Humanize('price',priceDiff) + ' р.');
		}
		else
		{
			self.$process
				.find('.other-found')
					.show()
					.find('.price-found')
						.text(Humanize('price',self.all.minPrice) + ' р.');
		}
		
		self.$process.show(300);
	}
	else
	{
		self.showFinished();
		
	}
};

Search.prototype.notFound = function()
{
	var self = this;
	self.$noResults.show();
	self.$moreResults.hide();
	
	self.$filters.find('.wrap').hide();
	self.$loader.hide();
	
	self.$help.show();
};

Search.prototype.renderFirst = function()
{
	var self = this;
	self.first.shown = true;
	self.showMore(false);
	self.renderHotels(self.first.hotels);
	
	self.$filtersOverlay.hide();
}

Search.prototype.renderFinished = function(){
	var self = this;
	self.all.shown = true;
	self.renderHotels(self.all.hotels);
	self.showMore(!self.all.done);
	
	self.$filtersOverlay.hide();
}


Search.prototype.showFinished = function(){
	var self = this;
	
	self.$process.hide(300);
	
	self.shown = 0;
	self.current = 'finished';
	
	self.$items.find('.item').remove();
	
	self.renderFinished();
	
	self.$el.find('.show-finished').off('click');
	
};

Search.prototype.renderHotels = function(hotels){
	var self = this;
	
	var renderTime = new Date();
	
	if(self.debug) console.log('[ПОИСК] Рендерим результаты. Прошло с начала: '+(new Date - self.startDate)+'мс');
	
	$.each(hotels,function(i,hotel){
		if(hotel.tours.length > 0){
			var $item = self.buildHotel(hotel);
			self.populateTours($item, hotel.tours);
			self.$items.append($item);			
		}
	});
	
	self.$moreResults.find('.loader').hide();
	self.$moreResults.find('a').show();
	
	self.checkVisible();
	
	if(self.debug) console.log('[ПОИСК] Завершили рендер результатов за ' +(new Date - renderTime) + 'мс. Прошло с начала: '+(new Date - self.startDate)+'мс');
	
	self.rebuildFilters();
}

Search.prototype.buildHotel = function(hotel){
	var self = this;
	var $item = self.$template.clone();
	$item.removeClass('template');
	
	$item.attr('data-hotel', hotel.id);
	$item.attr('data-name', hotel.name.toLowerCase());
	$item.attr('data-stars', hotel.stars);


	if(hotel.types){
		$item.attr('data-active', hotel.types.active);
		$item.attr('data-relax', hotel.types.relax);
		$item.attr('data-family', hotel.types.family);
		$item.attr('data-health', hotel.types.health);
		$item.attr('data-city', hotel.types.city);
		$item.attr('data-beach', hotel.types.beach);
		$item.attr('data-deluxe', hotel.types.deluxe);

		var $types = $item.find('.types');

		if(hotel.types.active == 1) $types.append('<li class="type">Активный</li>');
		if(hotel.types.relax == 1) $types.append('<li class="type">Спокойный</li>');
		if(hotel.types.family == 1) $types.append('<li class="type">Семейный</li>');
		if(hotel.types.health == 1) $types.append('<li class="type">Лечебный</li>');
		if(hotel.types.city == 1) $types.append('<li class="type">Городской</li>');
		if(hotel.types.beach == 1) $types.append('<li class="type">Пляжный</li>');
		if(hotel.types.deluxe == 1) $types.append('<li class="type deluxe">Эксклюзивный</li>');
	}
	
	$item.find('.title a').text(hotel.name.toLowerCase()).attr('href', hotel.hotelLink);

	if(!hotel.image)
		hotel.image = self.noImage;

	$item.find('.image .bg').css('background-image','url('+hotel.image+')');
	$item.find('.image a').attr('href', hotel.hotelLink);
	$item.find('.about .description').text(hotel.description);
	
	var $stars = $item.find('.stars');
	var stars = parseInt(hotel.stars);
	
	for(var s = 0; s < 5; s++){
		$stars.append((s < stars) ? '<i class="star ion-ios-star"></i>' : '<i class="no-star ion-ios-star-outline"></i>');
	}
	
	if(hotel.hotelrating != 0){
		$item.find('.review strong').text(hotel.rating);
		$item.find('.review span').text(Humanize('rating',hotel.rating));
	}else{
		$item.find('.review').hide();
	}
	
	
	return $item;
};

Search.prototype.populateTours = function($item, tours){
	var self = this;
	var meals = [];
	
	$item.find('.variants .variant').not('.template').remove();
	
	$item.find('.more').removeClass('hidden').off('click').on('click',function(){
		$(this).addClass('hidden');
		$item.find('.variants .variant').not('.template').show(100);
		return false;
	});
	
	$item.find('.other .variants-open').off('click').on('click',function(){
		$(this).siblings('.variants-close').show();
		$(this).hide();
		$item.find('.variants').show(100);
		return false;
	});
	
	$item.find('.other .variants-close').off('click').on('click',function(){
		$(this).siblings('.variants-open').show();
		$(this).hide();
		$item.find('.variants').hide();
		return false;
	});
	
	if(tours.length <= 5) $item.find('.more').addClass('hidden');
	
	$.each(tours,function(i, tour){
		
		if(i != 0)
		{
			var $variant = self.$variant.clone();
			
			$variant.removeClass('template');
			if(i > 4) $variant.hide();
			
			$variant.attr('data-price', tour.price);
			
			$variant.find('.price a').text(Humanize('price',tour.price) + ' р.').attr('href', self.tourlink+tour.tourid);
			
			var dateTo = moment(tour.flydate,'DD.MM.YYYY');
			$variant.find('.date span').text(dateTo.format('D MMMM'));
			$variant.find('.date small').text(Humanize('nights',tour.nights));
			
			$variant.find('.room span').text(tour.room);
			
			$variant.find('.meal span').text(tour.mealrussian);
			
			$variant.find('.operator .icon img').attr('src',$variant.find('.operator .icon img').data('src').replace('{id}',tour.operatorcode)).attr('alt',tour.operatorname);
			$variant.find('.operator span').text(tour.operatorname);
			
			$item.find('.variants .items').append($variant);
			
		}
		
	});
	
	//Min price
	var tour = tours[0];

	$item.find('.sum .order').text(Humanize('price',tour.price) + ' р.').attr('href', self.tourlink+tour.tourid);
	
	if(tour.price < self.filters.params.minPrice) self.filters.params.minPrice = parseInt(tour.price);
	if(tour.price > self.filters.params.maxPrice) self.filters.params.maxPrice = parseInt(tour.price);
	
	$item.attr('data-price', tour.price);

	var dateTo = moment(tour.flydate,'DD.MM.YYYY');
	$item.find('.icons .date span').text(dateTo.format('D MMMM'));
	$item.find('.icons .date small').text(Humanize('nights',tour.nights));
	$item.find('.icons .room span').text(tour.room);
	$item.find('.icons .meal span').text(tour.mealrussian);
	
	$item.find('.icons .operator img').attr('src',$item.find('.icons .operator img').data('src').replace('{id}',tour.operatorcode)).attr('alt',tour.operatorname);
	$item.find('.icons .operator span').text(tour.operatorname);
};


Search.prototype.showMore = function(show)
{	
	var self = this;	
	self.$moreResults.find('.loader').show().find('span').hide();
	self.$moreResults.find('a').hide();
	self.$moreResults.toggle(show);
	self.$help.toggle(!show);
}


//FILTERS

Search.prototype.filterHotels = function(hotels){
	var self = this;
	var params = self.filters.params;
	
	
	var filtered = hotels.filter(function(hotel){
		
		/*//Name
		var name = new RegExp(params.name,'i');
		if(!hotel.hotelname.match(name)) return false;
		*/
		
		//Tours
		hotel.filteredTours = hotel.tours.filter(function(tour){
			return tour;
		});
		
		return hotel;
	});
	
	return filtered;
}

Search.prototype.bindFiltersActions = function(){
	var self = this;
	var params = self.filters.params;
	var $stars = self.$params.find('.stars');
	
	self.$el.find('.sidebar .content').stick_in_parent({
		offset_top: 80
	});
	
	$stars.find('a').on('click',function(){
		
		var html = $(this).html();
		var star = $(this).data('stars');
		$stars.find('button .text').html(html);
		
		self.form.data.stars = star;
		self.form.$el.find('.search-button button').click();

		return false;
	});
	
	var $meals = self.$params.find('.meals');
	
	$meals.find('a').on('click',function(){		
		
		var html = $(this).html();
		var meal = $(this).data('meal');
		$meals.find('button .text').html(html).find('small').hide();
		
		self.form.data.meal = meal;
		self.form.$el.find('.search-button button').click();
		
		return false;
	});
	
	
	self.$filters.find('#types input').on('change',function(){
		
		var checked = $(this).is(':checked');
		var type = $(this).val();
		
		if(checked)
		{
			self.$items.find('.item[data-'+type+'="1"]:not(.template)').removeClass('hiddenTypes');
		}
		else
		{
			self.$items.find('.item[data-'+type+'="1"]:not(.template)').addClass('hiddenTypes');
		}
		
	});
	
	self.$filters.find('#filters-hotel').on('keyup',function(){
		
		var query = $(this).val();
		
		params.name = query;
		
		if(query.length > 0){
			self.$items.find('.item:not(.template)').addClass('hiddenName');
			
			self.$items.find('.item[data-name*="'+query.toLowerCase()+'"]:not(.template)').removeClass('hiddenName');
			
		}else{
			self.$items.find('.item:not(.template)').removeClass('hiddenName');
		}
		
		if(self.$items.find('.item:not(.hiddenName)').length == 0)
		{
			self.$noResults.show();
		}
		else
		{
			self.$noResults.hide();
		}
		
		self.checkVisible();
		
		return false;
	});
	
	self.$priceSlider.ionRangeSlider({
		type: "double",
		grid: true,
		min: 0,
		max: 0,
		from: 0,
		to: 0,
		postfix: " р.",
		hide_min_max: true,
		onFinish: function(data){
			self.filterPrice(data.from, data.to);
		}
	});

	self.priceSlider = self.$priceSlider.data('ionRangeSlider');
	
};

Search.prototype.filterPrice = function(from, to){
	var self = this;
	
	self.$items.find('.item:not(.template)').addClass('hiddenPrice').each(function(i, item){
		
		var price = parseInt($(item).attr('data-price'));
		
		if(price >= from && price <= to)
			$(item).removeClass('hiddenPrice');
		
	});
	
	
};

Search.prototype.filterParamsChanged = function(){
	var self = this;
	
	if(self.current == 'finished'){
		//self.finished.filtered = self.filterHotels(self.finished.data);
		self.showFinished();
	}
};

Search.prototype.rebuildFilters = function(){
	var self = this;
	
	self.priceSlider.update({
		min: self.filters.params.minPrice,
		max: self.filters.params.maxPrice,
		from: self.filters.params.minPrice,
		to: self.filters.params.maxPrice
	});
	
	/*var $stars = self.$filters.find('.stars');
	
	$stars.find('input').prop('disabled',true);
	$stars.find('input').prop('checked',false);
	
	$.each(active.stars, function(i,star){
		$stars.find('#stars-'+star).prop('disabled',false);
		$stars.find('#stars-'+star).prop('checked',true);
		
	});
	
	
	var $meals = self.$filters.find('.meals');
	
	$meals.find('input').prop('disabled',true);
	$meals.find('input').prop('checked',false);
	
	$.each(active.meals, function(i,meal){
		console.log(meal);
		$meals.find('#meal-'+meal).prop('disabled',false);
		$meals.find('#meal-'+meal).prop('checked',true);
		
	});*/
	
}

