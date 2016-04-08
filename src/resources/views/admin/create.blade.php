@extends('core::admin.master')

@section('title', trans('users::global.New'))

@section('main')

    @include('core::admin._button-back', ['module' => 'users'])
    <h1>
        @lang('users::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-users'))->multipart()->role('form') !!}
        @include('users::admin._form')
    {!! BootForm::close() !!}

@endsection
