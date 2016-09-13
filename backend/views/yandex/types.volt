<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li>Яндекс</li>
	<li class="active">{{ title }}</li>
</ol>
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ title }}</h4>
	</div>
	<div class="panel-body">
		<div class="table_pagination">
			<table class="table">
				<thead>
				<tr>
					<th>Яндекс ID</th>
					<th>Название в Яндексе</th>
					<th>Турвизор ID</th>
					<th>Название в Турвизоре</th>
				</tr>
				</thead>
				<tbody>
				{% for item in page.items %}
					{% if item.reference.tourvisor.id is defined %}
						{% set t_id = item.reference.tourvisor.id %}
					{% else %}
						{% set t_id = '' %}
					{% endif %}

					{% if item.reference.tourvisor.name is defined %}
						{% set t_name = item.reference.tourvisor.name %}
					{% else %}
						{% set t_name = '' %}
					{% endif %}

					<tr{% if t_id == '' %} class="table-danger"{% endif %}>
						<td>{{ item.id }}</td>
						<td>{{ item.name }}</td>
						<td>{{ t_id }}</td>
						<td>
							<input class="typeahead" type="text"
								   name="{{ type }}_{{ t_id }}"
								   value="{{ t_name }}"
								   data-yandex-id="{{ item.id }}"
								   data-tourvisor-id="{{ t_id }}" />
							<a href="#" class="remove" tabindex="-1" alt="Удалить привязку">
								<i class="glyphicon glyphicon-trash"></i>
							</a>
						</td>
					</tr>
				{% else %}
					<tr>
						<td colspan="4" class="not-found">{{ title }} не найдены</td>
					</tr>
				{% endfor %}
				</tbody>
			</table>

			{% if page.items %}
				<ul class="pagination">
					{% if page.before != page.current %}
						<li class="paginate_button">
							<a href="{{ url('yandex') }}/type/{{ type }}?page={{ page.before }}">назад</a>
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
							<a href="{{ url('yandex') }}/type/{{ type }}?page={{ i }}">{{ i }}</a>
						</li>
					{% endfor %}

					{% if page.next != page.current %}
						<li>
							<a href="{{ url('yandex') }}/type/{{ type }}?page={{ page.next }}">вперед</a>
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
		$('.typeahead').on('focus',function(){
			$(this).autocomplete({
				source: "{{ url('yandex/ajaxGet/') }}{{ type }}",
				minLength: 2,
				select: function(event, ui){
					event.preventDefault();
					$(this).val(ui.item.name);
					$(this).data('tourvisor-id', ui.item.id);
					$(this).parent().prev().text(ui.item.id)
							.parent().removeClass('table-danger');

					$.post('{{ url('yandex/ajaxAddReference/') }}{{ type }}', {
						id: ui.item.id,
						ya_ref_id: $(this).data('yandex-id')
					});
				}
			}).autocomplete( "instance" )._renderItem = function( ul, item ) {
				return $( "<li>" )
						.append( item.name )
						.appendTo( ul );
			};
		});

		$('.remove').on('click', function(){
			var $self = $(this);
			var id = $(this).prev().data('tourvisor-id');
			var ya_ref_id = $(this).prev().data('yandex-id');

			$.post('{{ url('yandex/ajaxDeleteReference/') }}{{ type }}', {
				id: id,
				ya_ref_id: ya_ref_id
			}, function(status){
				if(status)
				{
					$self.prev().val('').parent().prev().text('');
					$self.parent().parent().addClass('table-danger');
				}
			}, 'json');

			return false;
		});
	});
</script>