@extends('core::admin.master')

@section('title', __('users::global.New password'))

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

        <h1>@lang('users::global.New password')</h1>

        {!! BootForm::email(__('Email'), 'email')->addClass('input-lg')->autofocus(true) !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('input-lg') !!}
        {!! BootForm::password(__('Password confirmation'), 'password_confirmation')->addClass('input-lg') !!}
        {!! BootForm::hidden('token')->value($token) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('users::global.Change Password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
