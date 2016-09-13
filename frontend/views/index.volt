{{ partial('partials/header') }}

<main>
	{{ partial('partials/navbar') }}

	{{ content() }}

	{{ partial('partials/footerMenu') }}

	{{ partial('partials/modals') }}

	{{ partial('partials/find-tour-modal') }}
</main>

{{ partial('partials/footer') }}