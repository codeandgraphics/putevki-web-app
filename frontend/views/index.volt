{{ partial('partials/header') }}

<main class="{{ page }}">
	{% if page !== 'main' %}
	{{ partial('partials/navbar') }}
	{% endif %}

	{{ content() }}

	{{ partial('partials/footerMenu') }}

	{{ partial('partials/modals') }}

	{{ partial('partials/find-tour-modal') }}
</main>

{{ partial('partials/mobile-overlay') }}

{{ partial('partials/footer') }}