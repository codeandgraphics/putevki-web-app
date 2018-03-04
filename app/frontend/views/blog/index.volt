<div class="page" id="blog">
	<div class="container flex">
		<section class="main">
			<div class="head">
				<h1>
					Блог о путешествиях
					<small></small>
				</h1>
			</div>
			<div class="content">
				<div class="posts">
					{% for item in pagination.items %}
					<div class="post row">
						<div class="col-xs-3">
							{% if item.post.preview %}
							<a href="{{ url('blog/') }}{{ item.post.uri }}">
								<img src="{{ images_url('blog') }}/{{ item.post.preview }}" />
							</a>
							{% endif %}
						</div>
						<div class="col-xs-9 about">
							<h3>
								<a href="{{ url('blog/') }}{{ item.post.uri }}">
									{{ item.post.title }}
								</a>
							</h3>
							<div class="info">
								Автор: <a href="{{ url('blog/author/') }}{{ item.author.uri }}">{{ item.author.name }}</a>,
								опубликовано {{ item.post.created }}
							</div>
							<p>
								{{ item.post.excerpt }}
							</p>
							<a href="{{ url('blog/') }}{{ item.post.uri }}" class="more">
								Продолжить чтение...
							</a>
						</div>
					</div>
					{% endfor %}
				</div>
				{% if pagination.items %}
					<div class="paginator">
						<ul class="pagination">
							{% if pagination.before !== pagination.current %}
								<li>
									<a href="{{ url('blog') }}?page={{ pagination.before }}">назад</a>
								</li>
							{% else %}
								<li class="disabled"><span>назад</span></li>
							{% endif %}

							{% for i in 1..pagination.total_pages %}
								<li {% if pagination.current === i %}class="active"{% endif %}>
									<a href="{{ url('blog') }}?page={{ i }}">{{ i }}</a>
								</li>
							{% endfor %}

							{% if pagination.next !== pagination.current %}
								<li>
									<a href="{{ url('blog') }}?page={{ pagination.next }}">вперед</a>
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