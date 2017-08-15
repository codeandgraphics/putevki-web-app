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
'paid'				: 'Оплачен',
'authorized'		: 'Авторизован',
'new'				: 'Новый',
'not authorized'	: 'Не авторизован',
'waiting'			: 'В ожидании',
'canceled'			: 'Отменен'
] %}

<ol class="breadcrumb">
	<li><a href="{{ backend_url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('payments') }}">Все платежи</a></li>
	<li class="active">Платеж {{ payment.getOrder() }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<div class="pull-right label text-lg label-{{ paymentClasses[payment.status] }}">
			{{ paymentTexts[payment.status] }}
		</div>
		<h4 class="panel-title">Платеж {{ payment.getOrder() }}</h4>
	</div>
	<div class="panel-body">
		<div class="payment">
			<div class="row">
				<div class="col-xs-3">Статус</div>
				<div class="col-xs-9">
					<div class="label label-{{ paymentClasses[payment.status] }}">
						{{ paymentTexts[payment.status] }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-3">Сумма</div>
				<div class="col-xs-9">
					<strong>{{ payment.sum }} руб.</strong>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-3">Дата создания</div>
				<div class="col-xs-9">
					<strong>{{ payment.creationDate|humanDate }}</strong>
				</div>
			</div>

			{% if payment.payDate %}
				<div class="row">
					<div class="col-xs-3">Дата оплаты</div>
					<div class="col-xs-9">
						<strong>{{ payment.payDate|humanDate }}</strong>
					</div>
				</div>
			{% endif %}

			{% if payment.status not in paymentStatuses %}
				<div class="row">
					<div class="col-xs-3">Ссылка на оплату</div>
					<div class="col-xs-9">
						<input class="form-control" value="{{ url('pay') }}/{{ payment.id }}" disabled/>
					</div>
				</div>
			{% endif %}

			{% if payment.request %}
				<div class="row">
					<div class="col-xs-3">Заявка</div>
					<div class="col-xs-9">
						<a href="{{ backend_url('requests') }}/edit/{{ payment.request.id }}">
							Заявка №{{ payment.request.id }}
						</a>
					</div>
				</div>
			{% endif %}

			{% if payment.status is 'authorized' %}
				<div class="well well-sm text-center" style="margin-top:20px;">
					{% if payment.bill_number is null %}
						<p>
							Этот платеж только что был авторизован. Вы должны запросить код подтверждения, чтобы после бронирования можно было подтвердить авторизацию платежа.
						</p>
						<a href="{{ backend_url('payments/payment/') }}{{ payment.id }}?getOrderCode" class="btn btn-warning">Получить код подтверждения</a>

					{% elseif payment.auth_confirmed is false %}
						<p>
							Код авторизации: {{ payment.approval_code }}, номер заказа Uniteller: {{ payment.bill_number }}.<br/>
							После подтверждения авторизации платежа деньги спишутся с карты клиента в конце операционного дня
						</p>
						<a href="{{ backend_url('payments/payment/') }}{{ payment.id }}?confirmPayment" class="btn btn-success">
							Подтвердить авторизацию платежа
						</a>
					{% else %}
						<p>
							Платеж авторизован и подтвержден. Деньги спишутся с карты клиента в конце операционного дня
						</p>
					{% endif %}
				</div>
			{% endif %}

		</div>
	</div>
</div>