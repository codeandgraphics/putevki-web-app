{% if user.role == 'Admin' %}
<div class="panel panel-announcement">
	<div class="panel-heading">
		<h4 class="panel-title text-success">Статистика за сегодня</h4>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('requests') }}">
						<div class="icon fa fa-area-chart requests"></div>
					</a>
				</div>
				<div class="pull-left">
					<h4 class="panel-title">Новых заявок</h4>
					<h3>{{ today.requests.count }}</h3>
					{% if today.requests.count > 0 %}
					<h5 class="text-{{ today.requests.diff.class }}">{{ today.requests.diff.text }}</h5>
					{% else %}
					<h5>сегодня заявок не было</h5>
					{% endif %}
				</div>
			</div>
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('payments') }}">
						<div class="icon fa fa-credit-card payments"></div>
					</a>
				</div>
				<h4 class="panel-title">Оплачено заявок на</h4>
				<h3>{{ today.payments.count }} руб.</h3>
				{% if today.payments.count > 0 %}
				<h5 class="text-{{ today.payments.diff.class }}">{{ today.payments.diff.text }}</h5>
				{% else %}
				<h5>сегодня оплат не было</h5>
				{% endif %}
			</div>
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('tourists') }}">
						<div class="icon fa fa-user tourists"></div>
					</a>
				</div>
				<h4 class="panel-title">Новых туристов</h4>
				<h3>{{ today.tourists.count }}</h3>
				{% if today.tourists.count > 0 %}
				<h5 class="text-{{ today.tourists.diff.class }}">{{ today.tourists.diff.text }}</h5>
				{% else %}
				<h5>сегодня туристов не было</h5>
				{% endif %}
			</div>
		</div>
	</div>
</div>

<div class="panel panel-announcement">
	<div class="panel-heading">
		<h4 class="panel-title text-success">Статистика за неделю</h4>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('requests') }}">
						<div class="icon fa fa-area-chart requests"></div>
					</a>
				</div>
				<div class="pull-left">
					<h4 class="panel-title">Новых заявок</h4>
					<h3>{{ week.requests.count }}</h3>
					{% if week.requests.count > 0 %}
					<h5 class="text-{{ week.requests.diff.class }}">{{ week.requests.diff.text }}</h5>
					{% else %}
					<h5>на этой неделе заявок не было</h5>
					{% endif %}
				</div>
			</div>
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('payments') }}">
						<div class="icon fa fa-credit-card payments"></div>
					</a>
				</div>
				<h4 class="panel-title">Оплачено заявок на</h4>
				<h3>{{ week.payments.count }} руб.</h3>
				{% if week.payments.count > 0 %}
				<h5 class="text-{{ week.payments.diff.class }}">{{ week.payments.diff.text }}</h5>
				{% else %}
				<h5>на неделе оплат не было</h5>
				{% endif %}
			</div>
			<div class="col-xs-6 col-sm-4">
				<div class="pull-left">
					<a href="{{ url('tourists') }}">
						<div class="icon fa fa-user tourists"></div>
					</a>
				</div>
				<h4 class="panel-title">Новых туристов</h4>
				<h3>{{ week.tourists.count }}</h3>
				{% if week.tourists.count > 0 %}
				<h5 class="text-{{ week.tourists.diff.class }}">{{ week.tourists.diff.text }}</h5>
				{% else %}
				<h5>на неделе туристов не было</h5>
				{% endif %}
			</div>
		</div>
	</div>
</div>
{% endif %}