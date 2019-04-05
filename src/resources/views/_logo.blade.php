<div class="auth-container-logo">
    @if (TypiCMS::hasLogo())
        @include('core::public._logo')
    @else
        <h1>{{ config('app.name') }}</h1>
    @endif
</div>
