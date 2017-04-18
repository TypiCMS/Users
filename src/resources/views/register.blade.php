@extends('core::admin.master')

@section('title', __('Register'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('content')

<div id="register" class="container-register container-xs-center">

    @include('users::_status', ['closable' => false])

    {!! BootForm::open() !!}

        <h1>{{ __('Register') }}</h1>

        {!! BootForm::email(__('Email'), 'email')->addClass('input-lg') !!}
        {!! BootForm::text(__('First name'), 'first_name')->addClass('input-lg') !!}
        {!! BootForm::text(__('Last name'), 'last_name')->addClass('input-lg') !!}
        {!! BootForm::password(__('Password'), 'password')->addClass('input-lg') !!}
        {!! BootForm::password(__('Password confirmation'), 'password_confirmation')->addClass('input-lg') !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('Register'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
