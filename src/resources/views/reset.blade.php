@extends('core::admin.master')

@section('title', trans('users::global.New password'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('main')

<div id="login" class="container-newpassword container-xs-center">

    @include('core::admin._message', ['closable' => false])

    {!! BootForm::open() !!}

        <h1>@lang('users::global.New password')</h1>

        <div class="form-group">
            {!! Form::email('email')->addClass('form-control input-lg')->placeholder(trans('validation.attributes.email'))->autofocus(true) !!}
        </div>
        <div class="form-group">
            {!! Form::password('password')->addClass('form-control input-lg')->placeholder(trans('validation.attributes.password')) !!}
        </div>
        <div class="form-group">
            {!! Form::password('password_confirmation')->addClass('form-control input-lg')->placeholder(trans('validation.attributes.password_confirmation')) !!}
        </div>

        {!! BootForm::hidden('token')->value($token) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.Change password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
