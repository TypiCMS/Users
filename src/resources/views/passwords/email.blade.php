@extends('core::admin.master')

@section('title', __('Reset Password'))
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

<div id="reset" class="container-reset auth-container auth-container-sm">

    @include('users::_logo')

    {!! BootForm::open()->action(route(app()->getLocale().'::password.email'))->addClass('auth-container-form') !!}

        <h1 class="auth-container-title">{{ __('Reset Password') }}</h1>

        @include('users::_status')

        {!! BootForm::email(__('Email'), 'email')->addClass('form-control form-control-lg')->autofocus(true)->required() !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Send Password Reset Link'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
