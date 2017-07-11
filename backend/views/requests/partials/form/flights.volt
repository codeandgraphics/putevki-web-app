{% for flight in req.getFlights(direction) %}
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label>Номер рейса</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][number]" class="form-control" value="{{ flight.number }}" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Авиакомпания</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][company]" class="form-control" value="{{ flight.company }}" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Борт</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][plane]" class="form-control" value="{{ flight.plane }}" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Класс</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][class]" class="form-control" value="{{ flight.class }}" />
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-5">
					<div class="form-group">
						<label>Терминал вылета</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][departure][port]" class="form-control" value="{{ flight.departure.port }}" />
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label>Дата вылета</label>
						<div class="input-group">
							<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][departure][date]" class="form-control dp" value="{{ flight.departure.date }}" />
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Время вылета</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][departure][time]" class="form-control" value="{{ flight.departure.time }}" />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-5">
					<div class="form-group">
						<label>Терминал прилета</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][arrival][port]" class="form-control" value="{{ flight.arrival.port }}" />
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label>Дата прилета</label>
						<div class="input-group">
							<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][arrival][date]" class="form-control dp" value="{{ flight.arrival.date }}" />
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Время прилета</label>
						<input type="text" name="_flights{{ direction }}[{{ loop.index0 }}][arrival][time]" class="form-control" value="{{ flight.arrival.time }}" />
					</div>
				</div>
			</div>
		</div>
	</div>
	{% if loop.revindex !== 1 %}
		<hr/>
	{% endif %}
{% endfor %}


