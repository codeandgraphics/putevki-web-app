{{ partial('partials/navbar') }}
{{ partial('partials/modal/city') }}
{{ partial('partials/find-tour-modal') }}

<div class="hero little">
	<div class="container">
		{{ partial('partials/form-inline') }}
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form();
	});
</script>