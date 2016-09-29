<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        @if (TypiCMS::hasLogo())
            @include('core::public._logo')
        @endif

        <h2>@lang('users::global.Reset password')</h2>

        <p>
            @lang('users::global.To reset your password'),
            <a href="{{ route('changepassword', $token) }}">@lang('users::global.click here').</a></p>
        <p>
            @lang('users::global.Or point your browser to this address:') <br />
            {{ route('changepassword', $token) }}
        </p>
        <p>@lang('users::global.Thank you')</p>
    </body>
</html>
