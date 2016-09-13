<div class="form-group">
	<label for="hotelName">Название отеля</label>
	{{ form.render('hotelName', ['class': 'form-control']) }}
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="hotelCountry">Страна</label>
			{{ form.render('hotelCountry', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="hotelRegion">Курорт</label>
			{{ form.render('hotelRegion', ['class': 'form-control']) }}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label for="hotelDate">Дата заезда</label>
			<div class="input-group">
				{{ form.render('hotelDate', ['class': 'form-control dp']) }}
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="hotelNights">Ночей</label>
			{{ form.render('hotelNights', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="hotelPlacement">Размещение</label>
			{{ form.render('hotelPlacement', ['class': 'form-control']) }}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="hotelMeal">Питание</label>
			{{ form.render('hotelMeal', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="hotelRoom">Тип номера</label>
			{{ form.render('hotelRoom', ['class': 'form-control']) }}
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#hotelName').autocomplete({
			source: "{{ url('requests/ajaxHotels') }}",
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