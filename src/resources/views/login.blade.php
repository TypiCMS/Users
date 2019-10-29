@extends('core::admin.master')

@section('title', __('Login'))
@section('bodyClass', 'auth-background')

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('content')

<div id="login" class="container-login auth-container auth-container-sm">

    @include('users::_logo')

    {!! BootForm::open()->addClass('auth-container-form') !!}

        <h1 class="auth-container-title">{{ __('Login') }}</h1>

        @include('users::_status')

        {!! BootForm::email(__('Email'), 'email')->addClass('form-control-lg')->autofocus(true)->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('form-control-lg')->required() !!}

        <div class="form-group">
            {!! BootForm::checkbox(__('Remember Me'), 'remember') !!}
        </div>

        <div class="form-group">
            {!! BootForm::submit(__('Login'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

        <div class="form-group">
            <span class="form-text">
                <a href="{{ route(app()->getLocale().'::password.request') }}">{{ __('Forgot Your Password?') }}</a>
            </span>
        </div>

    {!! BootForm::close() !!}

    @if (config('typicms.register'))
    <p class="alert alert-warning alert-not-a-member">
        @lang('Not a member?') <a class="alert-link" href="{{ route(app()->getLocale().'::register') }}">@lang('Become a member')</a> @lang('and get access to all the content of our website.')
    </p>
    @endif

    <p class="auth-container-back-to-website">
        <a class="auth-container-back-to-website-link" href="{{ TypiCMS::homeUrl() }}"><span class="fa fa-angle-left fa-fw"></span>{{ __('Back to the website') }}</a>
    </p>

</div>

@endsection
