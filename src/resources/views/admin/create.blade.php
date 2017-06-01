@extends('core::admin.master')

@section('title', __('New user'))

@section('content')

    @include('core::admin._button-back', ['module' => 'users'])
    <h1>
        @lang('New user')
    </h1>

    {!! BootForm::open()->action(route('admin::index-users'))->multipart()->role('form') !!}
        @include('users::admin._form')
    {!! BootForm::close() !!}

@endsection
