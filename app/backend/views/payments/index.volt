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
	<li class="active">Все платежи</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Все платежи</h4>
		<div class="row">
			<div class="col-xs-12 col-sm-8 mb20">
				<p>
					Для создания ссылки на оплату введите сумму платежа в поле справа и нажмите "Создать"
				</p>
			</div>
			<div class="col-xs-12 col-sm-4">
				<form class="form-inline row" method="post">
					<div class="form-group col-xs-6">
						<input class="form-control" type="text" name="paymentSum" id="paymentSum" placeholder="10000.00" />
					</div>

					<div class="form-group col-xs-6">
					<button type="submit" class="btn btn-success btn-stroke btn-sm btn-block">Создать</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="table_pagination">
			<table class="table payments">
				<thead>
				<tr>
					<th>ID платежа</th>
					<th>Сумма</th>
					<th>Ссылка на оплату</th>
					<th>Номер заявки</th>
					<th>Статус заказа</th>
				</tr>
				</thead>
				<tbody>
				{% for payment in page.items %}
					<tr class="table-{{ paymentClasses[payment.status] }}" data-payment-id="{{ payment.id }}">
						<td>
							<h4>
								{% if payment.status === 'authorized' %}
									{% if payment.billNumber is null %}
										<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="Не получен код подтверждения"></i>
									{% elseif payment.authConfirmed is false %}
										<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="Не подтверждена авторизация"></i>
									{% endif %}
								{% endif %}
								<a href="{{ backend_url('payments/payment/') }}{{ payment.id }}">
									{{ payment.getOrder() }}
								</a>
							</h4>
						</td>
						<td>
							<h4>
								<?=\Utils\Text::humanize('price', $payment->sum);?><small> руб.</small>
							</h4>
						</td>
						<td>
							{% if payment.status not in paymentStatuses %}
								<u>{{ url('pay') }}/{{ payment.id }}</u>
							{% else %}
								<s>{{ url('pay') }}/{{ payment.id }}</s>
							{% endif %}
						</td>
						<td>
							{% if payment.request %}
								<a href="{{ backend_url('requests') }}/edit/{{ payment.request.id }}">
									Заявка №{{ payment.request.id }}
								</a>
							{% else %}
								не указан
							{% endif %}
						</td>
						<td class="text-right">
							<div class="label label-{{ paymentClasses[payment.status] }}">
								{{ paymentTexts[payment.status] }}
							</div>
						</td>
					</tr>
				{% else %}
					<tr>
						<td colspan="7" class="not-found">Пока нет платежей</td>
					</tr>
				{% endfor %}
				</tbody>
			</table>

			{% if page.items %}
				<ul class="pagination">
					{% if page.before != page.current %}
						<li class="paginate_button">
							<a href="{{ backend_url('payments') }}?page={{ page.before }}">назад</a>
						</li>
					{% else %}
						<li class="paginate_button disabled">
					<span>
						назад
					</span>
						</li>
					{% endif %}

					{% for i in 1..page.total_pages %}
						<li class="paginate_button{% if page.current == i %} active{% endif %}">
							<a href="{{ backend_url('payments') }}?page={{ i }}">{{ i }}</a>
						</li>
					{% endfor %}

					{% if page.next != page.current %}
						<li>
							<a href="{{ backend_url('payments') }}?page={{ page.next }}">вперед</a>
						</li>
					{% else %}
						<li class="disabled">
					<span>
						вперед
					</span>
						</li>
					{% endif %}
				</ul>
			{% endif %}
		</div>
	</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.payments button.remove').on('click', function(){
      if(confirm('Вы действительно хотите удалить этот платеж?'))
      {
        var $row = $(this).parent().parent();
        var paymentId = $row.data('payment-id');
        $.post("{{ backend_url('payments/delete') }}", { 'paymentId': paymentId}, function(response){
          $row.remove();
        }, 'json');
      }
      return false;
    });

    $('[data-toggle="tooltip"]').tooltip();
  });
</script>