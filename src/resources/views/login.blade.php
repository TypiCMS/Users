@extends('core::admin.master')

@section('title', __('Log in'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('content')

<div id="login" class="container-login container-xs-center">

    @include('users::_status', ['closable' => false])

    {!! BootForm::open() !!}

        @if (TypiCMS::hasLogo())
            @include('core::public._logo')
        @endif

        <h1>{{ __('Log in') }}</h1>

        {!! BootForm::email(('Email'), 'email')->addClass('input-lg')->autofocus(true)->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('input-lg')->required() !!}

        <div class="form-group">
            {!! BootForm::checkbox(__('Remember'), 'remember') !!}
        </div>

        <div class="form-group">
            {!! BootForm::submit(__('Log in'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

        <div class="form-group">
            <span class="help-block">
                <a href="{{ route('resetpassword') }}">{{ __('Forgot your password?') }}</a>
            </span>
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
