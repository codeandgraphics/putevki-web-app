$(document).ready(function(){

	var form = new Form();

	var tourvisorId = $('#search').attr('data-tourvisorId');

	var search = new Search(tourvisorId, form);
});
