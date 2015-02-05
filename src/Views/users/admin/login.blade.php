@extends('core::admin.master')

@section('page-header')
@stop
@section('sidebar')
@stop
@section('mainClass')
@stop

@section('main')

<div id="login" class="container-login container-xs-center">

    {!! BootForm::open()->role('form') !!}
        {!! BootForm::token() !!}
        <h1>@lang('users::global.Log in')</h1>

        {!! BootForm::email(trans('validation.attributes.email'), 'email')->addClass('input-lg') !!}
        {!! BootForm::password(trans('validation.attributes.password'), 'password')->addClass('input-lg') !!}

        <div class="form-group">
            <span class="help-block">
                <a href="{{ route('resetpassword') }}">@lang('users::global.Forgot your password?')</a>
            </span>
        </div>

        <div class="form-group">
            {!! BootForm::submit(trans('validation.attributes.log in'), 'btn-primary')->addClass('btn-lg btn-block') !!}
            {{-- Form::button(trans('validation.attributes.log in'), array('class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit')) --}}
        </div>

    {!! BootForm::close() !!}

</div>

@stop
