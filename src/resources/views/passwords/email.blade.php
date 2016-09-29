@extends('core::admin.master')

@section('title', trans('users::global.Reset Password'))

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

    @include('users::_status', ['closable' => false])

    {!! BootForm::open()->action(url('password/email')) !!}

        <h1>@lang('users::global.Reset Password')</h1>

        {!! BootForm::email(trans('validation.attributes.email'), 'email')->addClass('form-control input-lg')->autofocus(true) !!}

        <div class="form-group form-action">
            {!! BootForm::submit(trans('users::global.Send password reset link'), 'btn-primary')->addClass('btn-lg btn-block') !!}
        </div>

    {!! BootForm::close() !!}

</div>

@endsection
