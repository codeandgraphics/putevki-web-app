<div class="page country" id="countries">
	<div class="container flex">
		<section class="main left">
			<div class="head">
				<h1>
					{{ country.title }}
				</h1>
			</div>
			<div class="content">
				{{ country.about }}
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="wrap">
					<h3>Регионы</h3>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<div class="regions">
						{% for item in regions %}
							<div class="region">
								{{ item.tourvisor.name }}
							</div>
						{% endfor %}
					</div>
				</div>
			</div>
		</aside>
	</div>
</div>