<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}Number">Номер рейса</label>
					{{ form.render('flight%sNumber'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}Carrier">Авиакомпания</label>
					{{ form.render('flight%sCarrier'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}Plane">Борт</label>
					{{ form.render('flight%sPlane'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}Class">Класс</label>
					{{ form.render('flight%sClass'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-5">
				<div class="form-group">
					<label for="flight{{ direction }}DepartureTerminal">Терминал вылета</label>
					{{ form.render('flight%sDepartureTerminal'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="flight{{ direction }}DepartureDate">Дата вылета</label>
					<div class="input-group">
						{{ form.render('flight%sDepartureDate'|format(direction), ['class': 'form-control dp']) }}
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}DepartureTime">Время</label>
					{{ form.render('flight%sDepartureTime'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-5">
				<div class="form-group">
					<label for="flight{{ direction }}ArrivalTerminal">Терминал прилета</label>
					{{ form.render('flight%sArrivalTerminal'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="flight{{ direction }}ArrivalDate">Дата прилета</label>
					<div class="input-group">
						{{ form.render('flight%sArrivalDate'|format(direction), ['class': 'form-control dp']) }}
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="flight{{ direction }}ArrivalTime">Время</label>
					{{ form.render('flight%sArrivalTime'|format(direction), ['class': 'form-control']) }}
				</div>
			</div>
		</div>
	</div>
</div>


