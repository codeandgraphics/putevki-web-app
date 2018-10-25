<header class="{{ page }}">
	<div class="header-overlay"></div>
	<nav>
		<div class="header-bar">
			<div class="color-bar"></div>
			<div class="container" style="position: relative;">
				<ul class="links list-unstyled list-inline">
					<li>
						<a href="{{ url('/search/Москва/Турция') }}">Поиск туров</a>
					</li>
					<li>
						<a href="{{ url('/search/Без перелета/Турция') }}">Отели</a>
					</li>
					<li>
						<a href="{{ url('countries') }}">Страны</a>
					</li>
					<li>
						<a href="{{ url('blog') }}">Блог</a>
					</li>
					<li>
						<a href="{{ url('app') }}" target="_blank">Приложение</a>
					</li>
					<li>
						<a href="{{ url('about') }}">О нас</a>
					</li>
					<li>
						<a href="{{ url('contacts') }}">Контакты</a>
					</li>
				</ul>
				<div class="socials pull-right">
					<a href="https://fb.com/putevkionline" target="_blank" data-toggle="tooltip" data-title="Путевки в Facebook" data-placement="bottom">
						<i class="social-facebook-squared"></i></a>
					<a href="https://vk.com/onlineputevki" target="_blank" data-toggle="tooltip" data-title="Путевки Вконтакте" data-placement="bottom">
						<i class="social-vkontakte"></i></a>
					<a href="https://ok.ru/putevkiru" target="_blank" data-toggle="tooltip" data-title="Путевки в Одноклассниках" data-placement="bottom">
						<i class="social-odnoklassniki"></i></a>
				</div>
			</div>
		</div>
		<div class="main-header">
			<div class="container">
				<a class="brand" href="/"></a>
				<div class="phone">
					<div class="number">
						<a href="tel:+{{ city.phone|phone }}">{{ city.phone }}</a>
					</div>
					служба поддержки клиентов
				</div>
				<div class="request">
					<!-- <a href="//tours.putevki.ru" class="btn btn-default" target="_blank">
						<i class="ion-map"></i> Купить тур в офисе
					</a> -->
				</div>
				<div class="location">
					<i class="ion-location"></i>

					<a href="#" data-toggle="modal" data-target="#cityModal">
						<span>{{ city.name }}</span>
						<b class="caret"></b>
					</a>
				</div>
			</div>
		</div>
	</nav>
	<div class="hero">
		{% if page === 'main' %}
			<h1 class="title">Куда бы вы хотели поехать?</h1>
			{{ partial('partials/search-form') }}
		{% elseif page === 'search' %}
			{{ partial('partials/search-form') }}
		{% elseif page === 'country' and country.tourvisor.active %}
			{{ partial('partials/search-form') }}
		{% endif %}
	</div>
  <!-- 
		{% if page === 'main' %}
      <div class="blog-posts-container">
        <div class="blog-posts container">
          <div class="row">
            {% for post in posts %}
            <div class="col-xs-4">
              <div class="post">
              <div class="col-xs-4">
							<a href="{{ url('blog/') }}{{ post.uri }}">
								<img src="{{ images_url('blog') }}/{{ post.preview }}" />
							</a>
              </div>
              <div class="col-xs-8 text">
                <h4>
                  <a href="{{ url('blog/') }}{{ post.uri }}">
                    {{post.title}}
                  </a>
                </h4>
                <p>
                  {{post.excerpt}}
                </p>
                </div>
              </div>
            </div>
            {% endfor %}
          </div>
        </div>
      </div>
		{% endif %}
  -->
</header>
