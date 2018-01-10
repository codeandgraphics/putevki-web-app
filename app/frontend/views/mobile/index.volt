{{ partial('mobile/partials/header') }}

<main>
    {{ partial('mobile/partials/navbar') }}

    {{ content() }}

    {{ partial('mobile/partials/modals') }}
</main>

{{ partial('mobile/partials/footer') }}