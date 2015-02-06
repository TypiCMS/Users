@extends('core::admin.master')

@section('page-header')
@stop
@section('sidebar')
@stop
@section('mainClass')
@stop

@section('main')

<div id="reset" class="container-reset container-xs-center">

    {!! BootForm::open()->role('form') !!}
        {!! BootForm::token() !!}

        <h1>@lang('users::global.Reset password')</h1>

        <div class="form-group">
            {!! Form::email('email')->addClass('form-control input-lg')->placeholder(trans('validation.attributes.email')) !!}
        </div>

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.reset password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@stop
