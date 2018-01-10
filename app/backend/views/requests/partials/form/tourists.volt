<div class="panel">
	<div class="panel-heading">
		<button class="btn btn-success btn-stroke btn-sm pull-right" id="touristAddButton">Добавить туриста</button>
		<h4 class="panel-title">Туристы</h4>
		<p>Можно добавить туриста из базы, либо создать нового</p>
	</div>
	<div class="panel-body">
		<table class="table" id="touristsTable">
			<thead>
			<tr>
				<th></th>
				<th>Имя в паспорте</th>
				<th>Номер паспорта</th>
				<th>Годен до</th>
				<th>Выдан</th>
				<th>Дата рождения</th>
				<th>Гражданство</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			{% for tourist in tourists %}
				<tr class="tourist" data-tourist-id="{{ tourist.id }}">
					<td>
						{% if tourist.gender == 'm' %}
						<i class="fa fa-male"></i>
						{% else %}
						<i class="fa fa-female"></i>
						{% endif %}
					</td>
					<td>
						<a href="#" class="editable" data-name="passportSurname" data-pk="{{ tourist.id }}">{{ tourist.passportSurname }}</a>
						<a href="#" class="editable" data-name="passportName" data-pk="{{ tourist.id }}">{{ tourist.passportName }}</a>
						<input type="hidden" name="tourists[]" value="{{ tourist.id }}" />
					</td>
					<td>
						<a href="#" class="editable" data-name="passportNumber" data-pk="{{ tourist.id }}">
							{{ tourist.passportNumber }}
						</a>
					</td>
					<td>
						<a href="#" class="editable" data-name="passportEndDate" data-type="date" data-format="dd.mm.yyyy" data-pk="{{ tourist.id }}">
							{{ tourist.passportEndDate }}
						</a>
					</td>
					<td>
						<a href="#" class="editable" data-name="passportIssued" data-pk="{{ tourist.id }}">
							{{ tourist.passportIssued }}
						</a>
					</td>
					<td>
						<a href="#" class="editable" data-name="birthDate" data-type="date" data-format="dd.mm.yyyy" data-pk="{{ tourist.id }}">
							{{ tourist.birthDate }}
						</a>
					</td>
					<td>
						<a href="#" class="editable" data-name="nationality" data-pk="{{ tourist.id }}">
							{{ tourist.nationality }}
						</a>
					</td>
					<td>
						<a href="#" class="delete">
							<i class="fa fa-remove"></i>
						</a>
					</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
	</div>
</div>


<script type="text/javascript">

	$(document).ready(function(){

		$('#touristsTable .tourist a.editable').editable({
			url: '{{ backend_url('tourists/ajaxEditField') }}'
		});

		$('#touristsTable a.delete').click(function(){

			if(confirm('Вы действительно хотите удалить туриста?'))
			{
				$(this).parent().parent().remove();
			}

			return false;
		});

	});
</script>