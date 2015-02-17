<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>@lang('users::global.Reset password')</h2>

        <p>
            @lang('users::global.To reset your password'), 
            <a href="{{ route('changepassword', array('id' => $id, urlencode($code))) }}">@lang('users::global.click here').</a></p>
        <p>
            @lang('users::global.Or point your browser to this address:') <br />
            {{ route('changepassword', array('id' => $id, urlencode($code))) }}
        </p>
        <p>@lang('users::global.Thank you')</p>
    </body>
</html>
