@extends('core::admin.master')

@section('page-header')
@stop
@section('sidebar')
@stop
@section('mainClass')
@stop
@section('errors')
@stop

@section('main')

<div id="reset" class="container-reset container-xs-center">

    @include('core::admin._message', ['closable' => false])

    {!! BootForm::open()->role('form') !!}
        {!! BootForm::token() !!}

        <h1>@lang('users::global.Reset password')</h1>

        {!! BootForm::email(trans('validation.attributes.email'), 'email')->addClass('input-lg')->placeholder(trans('validation.attributes.email'))->hideLabel() !!}

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.reset password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@stop
