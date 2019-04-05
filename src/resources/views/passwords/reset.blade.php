@extends('core::admin.master')

@section('title', __('New password'))
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

<div id="login" class="container-newpassword auth-container auth-container-sm">

    @include('users::_logo')

    {!! BootForm::open()->action(route(app()->getLocale().'::password.request'))->addClass('auth-container-form') !!}

        <h1 class="auth-container-title">{{ __('New password') }}</h1>

        @include('users::_status')

        {!! BootForm::email(__('Email'), 'email')->addClass('form-control-lg')->autofocus(true)->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('form-control-lg')->required() !!}
        {!! BootForm::password(__('Password confirmation'), 'password_confirmation')->addClass('form-control-lg')->required() !!}
        {!! BootForm::hidden('token')->value($token) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Change Password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
