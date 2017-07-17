<div class="page post" id="blog">
	<div class="container flex">
		<section class="main left">
			<div class="head">
				<h1>
					{{ post.title }}
				</h1>
			</div>
			<div class="content">
				<ol class="breadcrumb">
					<li><a href="{{ url('blog') }}">Блог</a></li>
					<li><a href="{{ url('blog/author/') }}{{ post.author.uri }}">{{ post.author.name }}</a></li>
					<li class="active">{{ post.title }}</li>
				</ol>
				<div class="content">
					{{ post.content }}
				</div>
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="wrap">
					<h2>Еще от автора</h2>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<ul class="more-posts list-unstyled">
						{% for item in morePosts %}
							<li>
								<a href="{{ url('blog') }}/{{ item.uri }}">{{ item.title }}</a>
								<span>опубликовано {{ item.created }}</span>
							</li>
						{% endfor %}
					</ul>
				</div>
			</div>
		</aside>
	</div>
</div>