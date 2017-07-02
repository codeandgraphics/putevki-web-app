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
			<div class="col-sm-8">
				<p>
					Для создания ссылки на оплату введите сумму платежа в поле справа и нажмите "Создать"
				</p>
			</div>
			<div class="col-sm-4">
				<form class="form-inline pull-right" method="post">
					<div class="form-group">
						<input class="form-control" type="text" name="paymentSum" id="paymentSum" placeholder="10000" />
					</div>
					<button type="submit" class="btn btn-success btn-stroke btn-sm">Создать</button>
				</form>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="table_pagination">
			<table class="table payments">
				<thead>
				<tr>
					<th>Статус</th>
					<th>Сумма</th>
					<th>Ссылка на оплату</th>
					<th>Номер заявки</th>
					<th class="text-center">Дата оплаты</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				{% for payment in page.items %}
					<tr class="table-{{ paymentClasses[payment.status] }}" data-payment-id="{{ payment.id }}">
						<td>
							<div class="label label-{{ paymentClasses[payment.status] }}">
								{{ paymentTexts[payment.status] }}
							</div>
						</td>
						<td>
							<h4>
								<?=\Utils\Text::humanize('price', $payment->sum);?><small> руб.</small>
							</h4>
						</td>
						<td>
							{% if payment.status not in paymentStatuses %}
								<a href="{{ url('pay') }}/{{ payment.id }}" target="_blank">{{ url('pay') }}/{{ payment.id }}</a>
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
						<td>
							{{ payment.payDate }}
						</td>
						<td>
							{% if payment.status not in paymentStatuses %}
								<button class="btn btn-danger remove pull-right">
									<i class="fa fa-remove"></i>
								</button>
							{% endif %}
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
  });
</script>