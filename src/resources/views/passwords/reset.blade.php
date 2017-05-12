@extends('core::admin.master')

@section('title', __('New password'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('content')

<div id="login" class="container-newpassword container-xs-center">

    @include('users::_status', ['closable' => false])

    {!! BootForm::open()->action(url('password/reset')) !!}

        <h1>{{ __('New password') }}</h1>

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
