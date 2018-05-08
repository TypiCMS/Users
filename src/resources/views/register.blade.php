@extends('core::admin.master')

@section('title', __('Register'))
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

<div id="register" class="container-register small-container">

    @include('users::_logo')

    {!! BootForm::open()->addClass('small-container-form') !!}

        <h1 class="small-container-title">{{ __('Register') }}</h1>

        @include('users::_status', ['closable' => false])

        {!! BootForm::email(__('Email'), 'email')->addClass('form-control-lg')->required() !!}
        {!! BootForm::text(__('First name'), 'first_name')->addClass('form-control-lg')->required() !!}
        {!! BootForm::text(__('Last name'), 'last_name')->addClass('form-control-lg')->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('form-control-lg')->required() !!}
        {!! BootForm::password(__('Password confirmation'), 'password_confirmation')->addClass('form-control-lg')->required() !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Register'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
