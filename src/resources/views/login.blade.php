@extends('core::admin.master')

@section('title', __('Log in'))
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

<div id="login" class="container-login small-container">

    @include('users::_logo')

    {!! BootForm::open()->addClass('small-container-form') !!}

        <h1 class="small-container-title">{{ __('Log in') }}</h1>

        @include('users::_status', ['closable' => false])

        {!! BootForm::email(('Email'), 'email')->addClass('form-control-lg')->autofocus(true)->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('form-control-lg')->required() !!}

        <div class="form-group">
            {!! BootForm::checkbox(__('Remember'), 'remember') !!}
        </div>

        <div class="form-group">
            {!! BootForm::submit(__('Log in'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

        <div class="form-group">
            <span class="form-text">
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </span>
        </div>

    {!! BootForm::close() !!}

    <p class="small-container-back-to-website">
        <a class="small-container-back-to-website-link" href="{{ url('/') }}"><span class="fa fa-angle-left fa-fw"></span>{{ __('Back to website') }}</a>
    </p>

</div>

@endsection
