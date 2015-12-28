<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        @if (TypiCMS::hasLogo())
            @include('core::public._logo')
        @endif

        <h2>@lang('users::global.Welcome') {{ $user->first_name }} {{ $user->last_name }}</h2>

        <p><b>@lang('users::global.Account:')</b> {{ $user->email }}</p>
        <p>
            @lang('users::global.To activate your account'),
            <a href="{{ route('activate', $user->token) }}">@lang('users::global.click here').</a>
        </p>
        <p>
            @lang('users::global.Or point your browser to this address:') <br />
            {{ route('activate', $user->token) }}
        </p>
        <p>@lang('users::global.Thank you')</p>
    </body>
</html>
