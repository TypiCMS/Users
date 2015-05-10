<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        {!! TypiCMS::logo() !!}

        <h2>@lang('users::global.Welcome') {{ $first_name }} {{ $last_name }}</h2>

        <p><b>@lang('users::global.Account:')</b> {{ $email }}</p>
        <p>
            @lang('users::global.To activate your account'),
            <a href="{{ route('activate', array($id, urlencode($code))) }}">@lang('users::global.click here').</a>
        </p>
        <p>
            @lang('users::global.Or point your browser to this address:') <br />
            {{ route('activate', array($id, urlencode($code))) }}
        </p>
        <p>@lang('users::global.Thank you')</p>
    </body>
</html>
