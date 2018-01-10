<div class="page" id="blog">
	<div class="container flex">
		<section class="main">
			<div class="head">
				<h1>
					Блог {{ author.name }}
					<small></small>
				</h1>
			</div>
			<div class="content">
				<ol class="breadcrumb">
					<li><a href="{{ url('blog') }}">Блог</a></li>
					<li class="active">{{ author.name }}</li>
				</ol>
				<div class="author row">
					<div class="col-xs-2">
						<img src="{{ images_url('blog/bloggers/') }}{{ author.image }}" class="img-circle" />
					</div>
					<div class="col-xs-10">
						<h3 class="author-name">{{ author.name }}</h3>
						<p class="author-link">
							<a href="{{ url(author.link) }}" target="_blank">
								{{ author.link }}
							</a>
						</p>
						<p class="author-description">{{ author.description }}</p>
					</div>
				</div>
				<div class="posts">
					{% for post in pagination.items %}
						<div class="post row">
							<div class="col-xs-3">
								{% if post.preview %}
									<a href="{{ url('blog/') }}{{ post.uri }}">
										<img src="{{ images_url('blog') }}/{{ post.preview }}" />
									</a>
								{% endif %}
							</div>
							<div class="col-xs-9 about">
								<h3>
									<a href="{{ url('blog/') }}{{ post.uri }}">
										{{ post.title }}
									</a>
								</h3>
								<div class="info">
									Опубликовано {{ post.created }}
								</div>
								<p>
									{{ post.excerpt }}
								</p>
								<a href="{{ url('blog/') }}{{ post.uri }}" class="more">
									Продолжить чтение...
								</a>
							</div>
						</div>
					{% endfor %}
				</div>
				{% if pagination.items and pagination.total_pages > 1 %}
					<div class="paginator">
						<ul class="pagination">
							{% if pagination.before !== pagination.current %}
								<li>
									<a href="{{ url('blog/author/') }}{{ author.uri }}?page={{ pagination.before }}">назад</a>
								</li>
							{% else %}
								<li class="disabled"><span>назад</span></li>
							{% endif %}

							{% for i in 1..pagination.total_pages %}
								<li {% if pagination.current === i %}class="active"{% endif %}>
									<a href="{{ url('blog/author/') }}{{ author.uri }}?page={{ i }}">{{ i }}</a>
								</li>
							{% endfor %}

							{% if pagination.next !== pagination.current %}
								<li>
									<a href="{{ url('blog/author/') }}{{ author.uri }}?page={{ pagination.next }}">вперед</a>
								</li>
							{% else %}
								<li class="disabled"><span>вперед</span></li>
							{% endif %}
						</ul>
					</div>
				{% endif %}
			</div>
		</section>
	</div>
</div>