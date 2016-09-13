<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li>Яндекс</li>
	<li class="active">Отели</li>
</ol>
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Отели</h4>
	</div>
	<div class="panel-body">
		<div class="country">
			<select id="countries" name="countries" data-url="{{ url('yandex/hotels/') }}">
				{% for country in countries %}
					<option value="{{ country.id }}"{% if country.id == currentCountry %} selected{% endif %}>{{ country.name }}</option>
				{% endfor %}
			</select>
		</div>
		<div class="table_pagination">
			<table class="table">
				<thead>
				<tr>
					<th width="15%">Яндекс ID</th>
					<th width="35%">Название в Яндексе</th>
					<th width="15%">Турвизор ID</th>
					<th width="35%">Название в Турвизоре</th>
				</tr>
				</thead>
				<tbody>
				{% for item in page.items %}
					{% if item.reference.tourvisor.id is defined %}
						{% set t_id		= item.reference.tourvisor.id %}
						{% set t_name	= item.reference.tourvisor.name %}
					{% else %}
						{% set t_id		= '' %}
						{% set t_name	= '' %}
					{% endif %}

					<tr{% if t_id == '' %} class="table-danger"{% endif %}>
						<td>{{ item.id }}</td>
						<td>
							{{ item.name }}
							<small>{{ item.region.name }}</small>
						</td>
						<td>{{ t_id }}</td>
						<td class="input-remove">
							<input class="typeahead form-control" type="text"
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
						<td colspan="4" class="not-found">Отели не найдены</td>
					</tr>
				{% endfor %}
				</tbody>
			</table>

			{% if page.items %}
				<ul class="pagination">
					{% if page.before != page.current %}
						<li class="paginate_button">
							<a href="{{ url('yandex') }}/{{ type }}/{{ currentCountry }}?page={{ page.before }}">назад</a>
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
							<a href="{{ url('yandex') }}/{{ type }}/{{ currentCountry }}?page={{ i }}">{{ i }}</a>
						</li>
					{% endfor %}

					{% if page.next != page.current %}
						<li>
							<a href="{{ url('yandex') }}/{{ type }}/{{ currentCountry }}?page={{ page.next }}">вперед</a>
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
				source: "{{ url('yandex/ajaxGet/') }}{{ type }}{% if type == 'hotels' %}/{{ currentCountry }}{% endif %}",
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
						.append( item.name + '<br/><small>' + item.region + '</small>' )
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

		$('#countries').change(function(){

			var link = $('#countries').data('url');

			window.location.href = link + $(this).val();

		});
	});
</script>