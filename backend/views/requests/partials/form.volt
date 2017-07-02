{% set paymentStatuses = ['paid', 'authorized', 'waiting'] %}
{% set paymentClasses = [
'paid'				: 'success',
'authorized'		: 'warning',
'new'				: 'primary',
'not authorized'	: 'danger',
'waiting'			: 'info',
'canceled'			: 'danger'
] %}
{% set paymentTexts = [
'paid'				: 'Оплачено',
'authorized'		: 'Авторизовано',
'new'				: 'Новая',
'not authorized'	: 'Не авторизовано',
'waiting'			: 'В ожидании',
'canceled'			: 'Отменено'
] %}
<div class="row">
	<div class="col-sm-8">

		<!-- Информация об отеле -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Информация об отеле</h4>
			</div>
			<div class="panel-body">
				{{ partial('requests/partials/form/hotel') }}
			</div>
		</div>
		<!-- Информация об отеле -->

		<!-- Перелет туда -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Перелет туда</h4>
			</div>
			<div class="panel-body">
				{{ partial('requests/partials/form/flight', ['direction':'To']) }}
			</div>
		</div>
		<!-- Перелет туда -->

		<!-- Перелет обратно -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Перелет обратно</h4>
			</div>
			<div class="panel-body">
				{{ partial('requests/partials/form/flight', ['direction':'From']) }}
			</div>
		</div>
		<!-- Перелет обратно -->

		<!-- Комментарий к туру -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Дополнительная информация</h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="comment">Пожелания заказчика</label>
					{{ form.render('comment', ['class': 'form-control']) }}
				</div>
			</div>
		</div>
		<!-- Комментарий к туру -->
	</div>

	<div class="col-sm-4">
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Оплата</h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="price">Стоимость тура в рублях</label>
					{% if req.payments[0] is defined %}
					{% if req.payments[0].status === 'new' %}
						<div class="input-group">
							{{ form.render('price', ['class': 'form-control']) }}
							<div class="input-group-addon">руб.</div>
						</div>
					{% else %}
						<div class="input-group">
							{{ form.render('price', ['class': 'form-control', 'disabled': 'disabled']) }}
							<div class="input-group-addon">руб.</div>
						</div>
					{% endif %}
					{% endif %}
					<span class="help-block">Финальная цена тура, на эту сумму будет сформирована ссылка на оплату</span>
				</div>
				{% if req is defined %}
				{% if req.payments|length > 1 %}
				<label>Доплаты:</label>
				<table class="table table-condensed">
					{% for index, payment in req.payments %}
						{% if index != 0 %}
						<tr class="table-{{ paymentClasses[payment.status] }}" data-payment-id="{{ payment.id }}">
							<td>
								<?=\Utils\Text::humanize('price', $payment->sum);?> руб.
							</td>
							<td>
								<div class="label label-{{ paymentClasses[payment.status] }} pull-right">
									{{ paymentTexts[payment.status] }}
								</div>
							</td>
						</tr>
						{% endif %}
					{% endfor %}
				</table>
				<br/>
				{% endif %}
				<a href="{{ backend_url('payments/') }}?request={{ req.id }}" target="_blank" class="btn btn-default btn-block btn-xs btn-stroke file">
					<i class="fa fa-money"></i>
					<span>Все платежи</span>
				</a>
				{% endif %}
			</div>
		</div>
		<!-- Статус заявки -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Параметры заявки</h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="requestStatusId">Статус заявки</label>
					{{ form.render('requestStatusId', ['class': 'form-control']) }}
				</div>
				{% if user.role is 'Admin' %}
				<div class="form-group">
					<label for="branch_id">Филиал</label>
					{{ form.render('branch_id', ['class': 'form-control']) }}
				</div>
				{% endif %}
			</div>
		</div>
		<!-- Статус заявки -->

		<!-- Стоимость тура -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Параметры тура</h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="departureId">Город вылета</label>
					{{ form.render('departureId', ['class': 'form-control']) }}
					<span class="help-block">Город вылета из базы Tourvisor</span>
				</div>

				<div class="form-group">
					<label for="tourOperatorId">Туроператор</label>
					{{ form.render('tourOperatorId', ['class': 'form-control']) }}
					<span class="help-block">Туроператор из базы Tourvisor</span>
				</div>
			</div>
		</div>
		<!-- Стоимость тура -->

		<!-- Информация о заказчике -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Заказчик</h4>
				<p>Информация о заказчике тура, для составления договора</p>
			</div>
			<div class="panel-body">
				{{ partial('requests/partials/form/subject') }}
			</div>
		</div>
		<!-- Информация о заказчике -->

	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<!-- Туристы -->
		{{ partial('requests/partials/form/tourists') }}
		<!-- Туристы -->
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){

	});
</script>