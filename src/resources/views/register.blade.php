@extends('core::admin.master')

@section('title', __('users::global.Register'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('main')

<div id="register" class="container-register container-xs-center">

    @include('users::_status', ['closable' => false])

    {!! BootForm::open() !!}

        <h1>@lang('users::global.Register')</h1>

        {!! BootForm::email(__('validation.attributes.email'), 'email')->addClass('input-lg') !!}
        {!! BootForm::text(__('validation.attributes.first_name'), 'first_name')->addClass('input-lg') !!}
        {!! BootForm::text(__('validation.attributes.last_name'), 'last_name')->addClass('input-lg') !!}
        {!! BootForm::password(__('validation.attributes.password'), 'password')->addClass('input-lg') !!}
        {!! BootForm::password(__('validation.attributes.password_confirmation'), 'password_confirmation')->addClass('input-lg') !!}

        <div class="form-group form-action">
            {!! BootForm::submit(__('users::global.Register'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
