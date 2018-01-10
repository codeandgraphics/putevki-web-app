{% set hotel = req.getHotel() %}
<div class="form-group">
	<label>Название отеля</label>
	<input name="_hotel[name]" type="text" value="{{ hotel.name }}" class="form-control"/>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Страна</label>
			<input name="_hotel[country]" type="text" value="{{ hotel.country }}" class="form-control"/>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Курорт</label>
			<input name="_hotel[region]" type="text" value="{{ hotel.region }}" class="form-control"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label>Дата заезда</label>
			<div class="input-group">
				<input name="_hotel[date]" type="text" value="{{ hotel.date }}" class="form-control dp"/>
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Ночей</label>
			<input name="_hotel[nights]" type="text" value="{{ hotel.nights }}" class="form-control"/>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Размещение</label>
			<input name="_hotel[placement]" type="text" value="{{ hotel.placement }}" class="form-control"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Питание</label>
			<input name="_hotel[meal]" type="text" value="{{ hotel.meal }}" class="form-control"/>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Тип номера</label>
			<input name="_hotel[room]" type="text" value="{{ hotel.room }}" class="form-control"/>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#hotelName').autocomplete({
			source: "{{ backend_url('requests/ajaxHotels') }}",
			minLength: 3,
			select: function(event, ui){
				event.preventDefault();
				$('#hotelCountry').val(ui.item.country.name);
				$('#hotelRegion').val(ui.item.region.name);
				$('#hotelName').val(ui.item.name + ' ' + ui.item.stars.name);
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( item.name + " " + item.stars.name + "<br>" + item.region.name + ", " + item.country.name)
				.appendTo( ul );
		};

	});
</script>