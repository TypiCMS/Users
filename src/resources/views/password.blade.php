@extends('core::admin.master')

@section('title', trans('users::global.Reset password'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('main')

<div id="reset" class="container-reset container-xs-center">

    @include('core::admin._message', ['closable' => false])

    {!! BootForm::open() !!}

        <h1>@lang('users::global.Reset password')</h1>

        <div class="form-group">
            {!! Form::email('email')->addClass('form-control input-lg')->placeholder(trans('validation.attributes.email'))->autofocus(true) !!}
        </div>

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.reset password'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
