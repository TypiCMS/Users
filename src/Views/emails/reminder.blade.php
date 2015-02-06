<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>@lang('users::global.Reset password')</h2>

        <div>
            @lang('users::global.To reset your password, complete this form:') {{ route('resetpassword', array($token)) }}.
        </div>
    </body>
</html>
