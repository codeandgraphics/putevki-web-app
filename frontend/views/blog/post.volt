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
					<h2>Регионы</h2>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<div class="regions">
						{% for item in regions %}
							<div class="region">
								<a href="{{ url('countries/') }}{{ country.uri }}/{{ item.region.uri }}">
									<div class="image">
										<div class="bg" style="background-image: url('{{ images_url('regions/') }}{{ item.region.preview }}');"></div>
									</div>
									<div class="title">
										<h3>{{ item.tourvisor.name }}</h3>
									</div>
								</a>
							</div>
						{% endfor %}
					</div>
				</div>
			</div>
		</aside>
	</div>
</div>