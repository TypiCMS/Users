@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::email(trans('validation.attributes.email'), 'email')->autocomplete('off') !!}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::password(trans('validation.attributes.password'), 'password') !!}
    </div>
    <div class="col-sm-6">
        {!! BootForm::password(trans('validation.attributes.password_confirmation'), 'password_confirmation') !!}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {!! BootForm::text(trans('validation.attributes.first_name'), 'first_name') !!}
    </div>
    <div class="col-sm-6">
        {!! BootForm::text(trans('validation.attributes.last_name'), 'last_name') !!}
    </div>
</div>

<div class="form-group">
{!! BootForm::hidden('activated')->value(0) !!}
{!! BootForm::hidden('superuser')->value(0) !!}
{!! BootForm::checkbox(trans('validation.attributes.activated'), 'activated') !!}
{!! BootForm::checkbox(trans('validation.attributes.superuser'), 'superuser') !!}
</div>

@if ($roles = Roles::all() and $roles->count())
<div class="form-group">
    <label>@lang('validation.attributes.roles')</label>
    @foreach ($roles as $role)
    <div class="checkbox">
        <label>
            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @if (in_array($role->id, $selectedRoles))checked="checked"@endif> {{ $role->name }}
        </label>
    </div>
    @endforeach
</div>
@endif

<label>@lang('users::global.User permissions')</label>
@include('core::admin._permissions-form')
