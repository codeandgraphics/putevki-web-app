$(document).ready(function(){

	$('#messages div').each(function(i, message){

		var $message = $(message);

		console.log($message.attr('class'));

		$.notify({
			message: $message.html()
		},{
			type: $message.attr('class'),
			offset: {
				x: 20,
				y: 70
			}
		});

	});

	$( ".dp" ).datepicker({
		dateFormat: 'dd.mm.yy',
		changeMonth: true,
		changeYear: true
	});

});