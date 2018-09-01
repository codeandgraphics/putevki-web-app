<div class="page content {{ page }}">
	<div class="container flex">
		<section class="main">
			<div class="head">
				<h1>
					 Путевки и туры от {{ operator.fullName }}
            <small>{{ operator.russian }}</small>
				</h1>
			</div>
			<div class="content">
        <div class="row about">
          <div class="col-xs-2">
            <img src="http://tourvisor.ru/pics/operators/mobilelogo/{{ operator.id }}.png" class="img-responsive" />
          </div>
          <div class="col-xs-5">
            Путёвки.ру является официальным партнером туроператора <b>{{ operator.russian }}</b>. 
            Мы предлагаем туры по различным направлениям от туроператора.
          </div>
          <div class="col-xs-5">
            Цены на туры и путевки от туроператора <b>{{ operator.russian }}</b> финальные и включают 
            проживание в отеле, перелет, трансфер и медицинскую страховку.
          </div>
        </div>
        <div 
          class="tv-search-form tv-moduleid-167195" 
          tv-operatorsfilter="{{ operator.id }}" 
          tv-departure="{{ city.departure.id }}"
        ></div>
        <script type="text/javascript" src="//tourvisor.ru/module/init.js"></script>

        <div class="info">
          <h2>
            {{ operator.fullName}}
            <small>{{ operator.russian }}</small>
          </h2>
          {{ operator.about }}
        </div>
      </div>
    </div>
</div>