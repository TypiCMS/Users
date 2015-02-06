<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>@lang('users::global.Welcome') {{ $firstName }} {{ $lastName }}</h2>

        <p><b>@lang('users::global.Account:')</b> {{{ $email }}}</p>
        <p>
            @lang('users::global.To activate your account'), 
            <a href="{{ route('activate', array($userId, urlencode($activationCode))) }}">@lang('users::global.click here').</a>
        </p>
        <p>
            @lang('users::global.Or point your browser to this address:') <br />
            {{ route('activate', array($userId, urlencode($activationCode))) }}
        </p>
        <p>@lang('users::global.Thank you')</p>
    </body>
</html>
