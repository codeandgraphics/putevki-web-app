$(document).ready(function(){

	moment.locale('ru');

	$('.grid').masonry({
		itemSelector: '.grid-item',
		columnWidth: 414
	});

	$('#hotel .sidebar .content').stick_in_parent({
		offset_top: 80
	});

	var form = new Form();
	var hotelForm = new HotelForm(form);

	if(window.location.hash === '#tours')
	{
		$(window).scrollTo('#tours', 300, {
			offset: {
				top: -100
			}
		});
	}

	form.$el.find('.search button').off('click').on('click',function()
	{
		var self = form;

		hotelForm.$no.hide();
		hotelForm.$loader.show();
		hotelForm.$variants.html('');
		hotelForm.$more.hide();

		var data = {
			from		: parseInt(self.$el.find('.from select').val()),
			where		: self.data.where,
			adults		: self.data.adults,
			kids		: (self.data.kids) ? self.data.kids.join('+') : 0,
			stars		: self.data.stars,
			meal		: self.data.meal,
			hotel		: self.data.hotel,
			operator	: self.data.operator,
			country		: self.data.country,
			region		: self.data.region
		};

		data.date = (self.data['date_range']) ? '~' + self.data.date : self.data.date;
		data.nights = (self.data['nights_range']) ? '~' + self.data.nights : self.data.nights;

		$.getJSON(self.endpoint+'searchHotel/',{
			params	: data
		},function(res){
			hotelForm.start(res.tourvisorId);
		});
		return false;
	}).click();

});