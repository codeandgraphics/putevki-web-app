<?php
$people = (int) $tour->adults + (int) $tour->child;
?>
<div class="tourists">
	<h2>
		{% if type == 'request'  %}
		<div class="check-later">
			<input type="checkbox" id="tourists-later" value="active" checked="checked">
			<label for="tourists-later">Заполню позже</label>
		</div>
		{% endif %}
		Данные туристов ({{ people }})
	</h2>

	{% if type == 'request' %}
	<div class="message later">
		После отправки заявки на бронирование тура наш менеджер свяжется с вами и уточнит данные туристов
	</div>
	{% endif %}

	<div class="items{% if type == 'request' %} collapse{% endif %}">

		<p class="message"><i class="ion-alert-circled"></i> Заполняйте данные туристов строго как в заграничном паспорте!</p>

		<?php
		for($i = 0;$i<$people;$i++)
		{
			?>
			<div class="tourist">
				<div class="title">
					<?php
					if($tour->visacharge > 0)
					{
						?>
						<div class="visa form-group pull-right">
							<input type="checkbox" id="tourist-visa-<?=$i;?>" name="tourists[<?=$i;?>][visa]" data-price="<?=$tour->visacharge;?>">
							<label for="tourist-visa-<?=$i;?>"> Оформлять визу? <span>(+<?=number_format($tour->visacharge, 0, '.', ' ');?> р.)</span></label>
						</div>
						<?php
					}
					?>
					<h4><i class="ion-man"></i> Турист <span><?=$i+1;?></span></h4>
				</div>

				<div class="wrap">
					<div class="left">
						<div class="cell">
							<div class="first form-group">
								<label for="tourist-lastname-<?=$i;?>" class="control-label">Фамилия</label>
								<input type="text" class="form-control" id="tourist-lastname-<?=$i;?>" name="tourists[<?=$i;?>][lastname]" placeholder="по загранпаспорту" data-inputmask-regex="'mask': '[a-z]'" required>
							</div>
							<div class="second form-group">
								<label for="tourist-firstname-<?=$i;?>" class="control-label">Имя</label>
								<input type="text" class="form-control" id="tourist-firstname-<?=$i;?>" name="tourists[<?=$i;?>][firstname]" placeholder="по загранпаспорту" required>
							</div>
						</div>
						<div class="cell">
							<div class="first form-group">
								<label for="tourist-gender-<?=$i;?>">Пол</label>
								<label for="tourist-gender-male-<?=$i;?>" class="gender">
									<input type="radio" name="tourists[<?=$i;?>][gender]" id="tourist-gender-male-<?=$i;?>" value="man" checked>
									Мужской
								</label>
								<label for="tourist-gender-female" class="gender">
									<input type="radio" name="tourists[<?=$i;?>][gender]" id="tourist-gender-female-<?=$i;?>" value="woman">
									Женский
								</label>
							</div>
							<div class="second form-group">
								<label for="tourist-birth-<?=$i;?>" class="control-label">Дата рождения</label>
								<input type="text" class="form-control" id="tourist-birth-<?=$i;?>" name="tourists[<?=$i;?>][birth]" placeholder="01.01.1980" data-inputmask="'mask': '99.99.9999'" required>
							</div>
						</div>
					</div>

					<div class="right">
						<div class="cell">
							<div class="first form-group">
								<label for="tourist-nationality-<?=$i;?>" class="control-label">Гражданство</label>
								<input type="text" class="form-control" name="tourists[<?=$i;?>][nationality]" id="tourist-nationality-<?=$i;?>" placeholder="Россия" required>
							</div>
							<div class="second form-group">
								<label for="tourist-passport-<?=$i;?>" class="control-label">Номер загранпаспорта</label>
								<input type="text" class="form-control" name="tourists[<?=$i;?>][passport]" id="tourist-passport-<?=$i;?>" placeholder="00№0000000" data-inputmask="'mask': '99№9999999'" required>
							</div>
						</div>
						<div class="cell">
							<div class="first form-group">
								<label for="tourist-date-<?=$i;?>" class="control-label">Действителен до:</label>
								<input type="text" class="form-control" id="tourist-date-<?=$i;?>" name="tourists[<?=$i;?>][end_date]" placeholder="01.01.1980" data-inputmask="'mask': '99.99.9999'" required>
							</div>
							<div class="second form-group">
								<label for="tourist-issue-<?=$i;?>" class="control-label">Кем выдан:</label>
								<input type="text" class="form-control" id="tourist-issue-<?=$i;?>" name="tourists[<?=$i;?>][issue]" placeholder="ОУФМС 0000" required>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>