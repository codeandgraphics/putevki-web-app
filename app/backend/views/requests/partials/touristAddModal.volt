<div class="modal fade" id="touristAddModal" tabindex="-1" role="dialog" aria-labelledby="touristAddModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="touristAddModalLabel">Добавление туриста</h4>
			</div>
			<div class="modal-body">
				Начните вводить фамилию туриста, если он есть в базе, все данные подставятся автоматически
				<hr/>
				{{ partial('requests/partials/touristAddModalForm') }}

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-stroke-thin pull-left" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-stroke btn-sm btn-success" id="touristFormAddButton">Добавить туриста</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('#touristAddButton').off('click').on('click', function(){

			$('#touristAddModal').modal('show');

			return false;
		});

		$('#touristFormAddButton:not(.disabled)').off('click').on('click', function(){

			var $button = $(this);
			var $form = $('#touristAddModal form');
			var form = $form.serialize();

			$form.find('input').prop('disabled', true);

			$button.prop('disabled',true);

			$.post("{{ backend_url('tourists/ajaxAdd') }}", form, function(data){

				if(data.tourist && data.tourist.id)
				{
					var tourist = data.tourist;
					var $touristRow = $('<tr />');
					if(tourist.gender == 'm'){
						$touristRow.append('<td><i class="fa fa-male"></i></td>');
					}else{
						$touristRow.append('<td><i class="fa fa-female"></i></td>');
					}

					var hidden = '<input type="hidden" name="tourists[]" value="' + tourist.id + '" />';

					$touristRow.append('<td>'+tourist.passport_surname+' '+tourist.passport_name + hidden + '</td>');
					$touristRow.append('<td>'+tourist.passport_number+'</td>');
					$touristRow.append('<td>'+tourist.passport_endDate+'</td>');
					$touristRow.append('<td>'+tourist.passport_issued+'</td>');
					$touristRow.append('<td>'+tourist.birthDate+'</td>');

					var $delete = $('<a/>');
					$delete.append('<i class="fa fa-remove"></i>');
					$delete.on('click', function(){
						if(confirm('Вы действительно хотите удалить туриста?'))
						{
							$touristRow.remove();
						}
						return false;
					});

					$touristRow.append($('<td/>').append($delete));


					$('#touristsTable tbody').append($touristRow);

					$form.find('input').val('').prop('disabled', false);
					$button.prop('disabled', false);

					$('#touristAddModal').modal('hide');
				}
				else
				{
					$form.find('input').val('').prop('disabled', false);
					$button.prop('disabled', false);

					alert('Ошибка добавления туриста, проверьте данные!');
				}

			}, 'json');

			return false;
		});

		$('#tourist-passport-surname').autocomplete({
			source: "{{ backend_url('tourists/ajaxGet') }}",
			minLength: 3,
			select: function(event, ui){
				event.preventDefault();
				$('#tourist-passport-surname').val(ui.item.passport_surname);
				$('#tourist-passport-name').val(ui.item.passport_name);
				$('#tourist-passport-number').val(ui.item.passport_number);
				$('#tourist-passport-issued').val(ui.item.passport_issued);
				$('#tourist-passport-endDate').val(ui.item.passport_endDate);
				$('#tourist-gender').val(ui.item.gender);
				$('#tourist-birthDate').val(ui.item.birthDate);
				$('#tourist-phone').val(ui.item.phone);
				$('#tourist-email').val(ui.item.email);
				$('#tourist-id').val(ui.item.id);

				console.log(ui.item);
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( item.passport_name + " " + item.passport_surname + "<br>" + item.passport_number)
				.appendTo( ul );
		};

	});

</script>