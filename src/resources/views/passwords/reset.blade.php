@extends('core::admin.master')

@section('title', __('New password'))
@section('bodyClass', 'gray-background')

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('content')

<div id="login" class="container-newpassword small-container">

    @includeWhen(TypiCMS::hasLogo(), 'users::_logo')

    {!! BootForm::open()->action(url('password/reset'))->addClass('small-container-form') !!}

        @include('users::_status', ['closable' => false])

        <h1 class="small-container-title">{{ __('New password') }}</h1>

        {!! BootForm::email(__('Email'), 'email')->addClass('input-lg')->autofocus(true)->required() !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('input-lg')->required() !!}
        {!! BootForm::password(__('Password confirmation'), 'password_confirmation')->addClass('input-lg')->required() !!}
        {!! BootForm::hidden('token')->value($token) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Change Password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
