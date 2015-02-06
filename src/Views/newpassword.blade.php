@extends('core::admin.master')

@section('page-header')
@stop
@section('sidebar')
@stop
@section('mainClass')
@stop

@section('main')

<div id="login" class="container-newpassword container-xs-center">

    {!! BootForm::open()->role('form') !!}
        {!! BootForm::token() !!}

        <h1>@lang('users::global.New password')</h1>

        {!! BootForm::password(trans('validation.attributes.password'), 'password')->addClass('input-lg') !!}

        {!! BootForm::password(trans('validation.attributes.password_confirmation'), 'password_confirmation')->addClass('input-lg') !!}

        {!! BootForm::hidden('resetCode', $resetCode) !!}
        {!! BootForm::hidden('id', $id) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.modify'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@stop
