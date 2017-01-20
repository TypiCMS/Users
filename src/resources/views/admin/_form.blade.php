@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::email(__('validation.attributes.email'), 'email')->autocomplete('off') !!}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::password(__('validation.attributes.password'), 'password') !!}
    </div>
    <div class="col-sm-6">
        {!! BootForm::password(__('validation.attributes.password_confirmation'), 'password_confirmation') !!}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::text(__('validation.attributes.first_name'), 'first_name') !!}
    </div>
    <div class="col-sm-6">
        {!! BootForm::text(__('validation.attributes.last_name'), 'last_name') !!}
    </div>
</div>

<div class="form-group">
{!! BootForm::hidden('activated')->value(0) !!}
{!! BootForm::hidden('superuser')->value(0) !!}
{!! BootForm::checkbox(__('validation.attributes.activated'), 'activated') !!}
{!! BootForm::checkbox(__('validation.attributes.superuser'), 'superuser') !!}
</div>

@if ($roles = Roles::findAll() and $roles->count())
<div class="form-group">
    <label>@lang('validation.attributes.roles')</label>
    @foreach ($roles as $role)
    <div class="checkbox">
        <label>
            {!! Form::checkbox('roles[]', $role->id) !!} {{ $role->name }}
        </label>
    </div>
    @endforeach
</div>
@endif

<label>@lang('users::global.User permissions')</label>
@include('core::admin._permissions-form')
