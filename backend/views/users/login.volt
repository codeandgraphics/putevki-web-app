<body class="signwrapper">

<div class="sign-overlay"></div>
<div class="signpanel"></div>

<div class="panel signin">
	<div class="panel-heading">
		<h1>Путевки.ру</h1>
	</div>
	<div class="panel-body mt20">
		{{ form("users/login", 'autocomplete': 'off') }}
			<div class="form-group mb10">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					{{ text_field("email", 'class':'form-control', 'placeholder': 'E-mail', 'autocomplete': 'off') }}
				</div>
			</div>
			<div class="form-group nomargin">
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					{{ password_field("password", 'class':'form-control', 'placeholder': 'Пароль', 'autocomplete': 'off') }}
				</div>
			</div>
			<hr class="invisible" />
			<div class="form-group">
				<button class="btn btn-success btn-quirk btn-block">Войти</button>
			</div>
			<input type="hidden" name="<?php echo $this->security->getTokenKey() ?>" value="<?php echo $this->security->getToken() ?>"/>
		</form>
	</div>
</div><!-- panel -->

</body>