{{ partial('partials/modal/city') }}

{{ partial('partials/modal/callBack') }}

<div class="modal fade" id="onlineStatusModal" tabindex="-1" role="dialog" aria-labelledby="onlineStatusModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="process">
				<div class="loader">
					<div class="wrap">
						<div class="object"></div>
						<p class="status online">Отправляем запрос на покупку тура...</p>
						<p class="status request office find">Отправляем вашу заявку...</p>
					</div>
				</div>
			</div>
			<div class="message-success">
				<p class="online">Тур успешно забронирован! Перенаправляем вас на страницу оплаты.</p>
				<p class="request office find">Заявка отправлена.<br/>Наш менеджер свяжется с вами в ближайшее время!</p>
			</div>
			<div class="message-error">
				<p>Что-то пошло не так... Позвоните нам, или закажите звонок.</p>
			</div>
		</div>
	</div>
</div>