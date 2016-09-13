<h2>Регистрация</h2>

{{ form('users/register') }}

<p>
	<label for="name">Имя</label>
	{{ text_field("name") }}
</p>
<p>
	<label for="email">E-mail</label>
	{{ text_field("email") }}
</p>

<p>
	<label for="password">Пароль</label>
	{{ password_field("password") }}
</p>

<p>
	{{ submit_button("Регистрация") }}
</p>

</form>