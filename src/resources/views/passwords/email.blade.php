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

<div id="reset" class="container-reset small-container">

    @include('users::_logo')

    {!! BootForm::open()->action(route('password.email'))->addClass('small-container-form') !!}

        <h1 class="small-container-title">{{ __('Reset Password') }}</h1>

        @include('users::_status', ['closable' => false])

        {!! BootForm::email(__('Email'), 'email')->addClass('form-control form-control-lg')->autofocus(true)->required() !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Send password reset link'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
