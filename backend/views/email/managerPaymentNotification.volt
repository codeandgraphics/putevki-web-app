{{ partial('email/partials/headerBackend') }}

<div class="middle" style="-ms-text-size-adjust:100%;-webkit-font-smoothing:subpixel-antialiased;-webkit-text-size-adjust:100%;background-color:#f4f4f4;margin:0 10px;padding-bottom:20px;table-layout:fixed;text-align:center">
	<!--[if (gte mso 9)|(IE)]>
	<table width="600" align="center">
		<tr>
			<td>
	<![endif]-->
	<table align="center" cellpadding="0" class="outer" style="background-color:#fff;border-collapse:collapse;border-radius:4px;margin:0 auto;max-width:600px;width:100%">
		<tr>
			<td class="one-column content" style="-webkit-font-smoothing:subpixel-antialiased;font-size:0;padding:0;text-align:center">
				<div class="column" style="-webkit-font-smoothing:subpixel-antialiased;display:inline-block;font-size:14px;max-width:600px;text-align:left;vertical-align:top;width:100%">
					<table cellpadding="20" style="border-collapse:collapse" width="100%">
						<tr>
							<td style="-webkit-font-smoothing:subpixel-antialiased;padding-bottom:10px">
								<div class="h1" style="-webkit-font-smoothing:subpixel-antialiased;color:#333;font-family:helvetica,arial;font-size:24px;line-height:30px;text-align:left">
									Добрый день!
								</div>
								<p style="-webkit-font-smoothing:subpixel-antialiased;Margin:1em 0;color:#333;font-family:helvetica,arial;font-size:14px;line-height:20px;text-align:left">Поступила оплата заявки через Uniteller:</p>
								<table bgcolor="#F4F4F4" cellpadding="10" style="border-collapse:collapse" width="100%">
									<tr>
										<td style="-webkit-font-smoothing:subpixel-antialiased">
											<div class="h2" style="-webkit-font-smoothing:subpixel-antialiased;color:#333;font-family:helvetica,arial;font-size:18px;line-height:24px;padding-bottom:5px;text-align:left">
												Сумма платежа: {{ payment.sum }} руб.
											</div>
											<p style="-webkit-font-smoothing:subpixel-antialiased;Margin:1em 0;color:#333;font-family:helvetica,arial;font-size:14px;line-height:20px;text-align:left">
												Дата оплаты: <strong>{{ payment.payDate|humanDate }}</strong>
											</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="one-column content" style="-webkit-font-smoothing:subpixel-antialiased;font-size:0;padding:0;text-align:center">
				<div class="column" style="-webkit-font-smoothing:subpixel-antialiased;display:inline-block;font-size:14px;max-width:600px;text-align:left;vertical-align:top;width:100%">
					<table cellpadding="20" style="border-collapse:collapse" width="100%">
						<tr>
							<td align="center" style="-webkit-font-smoothing:subpixel-antialiased;padding-bottom:30px">
								<div style="-webkit-font-smoothing:subpixel-antialiased;text-align:center">
									<table class="button" style="border-collapse:collapse;margin:auto;width:auto">
										<tr>
											<td class="button-inner" style="-webkit-font-smoothing:subpixel-antialiased;padding:0"><a class="button-link" href="{{ adminUrl }}payments/payment/{{ payment.id }}" style="background-color:#fe8721;border:#fe8721 10px solid;border-radius:25px;color:#fff;display:inline-block;font-family:helvetica;font-size:14px;font-weight:700;padding:0 10px;text-decoration:none" target="_blank"><span style="color:#fff">Открыть в панели управления</span></a></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table><!--[if (gte mso 9)|(IE)]>
	</td>
	</tr>
	</table>
	<![endif]-->
</div>

{{ partial('email/partials/footerBackend') }}