@section('js')
    <script src="{{ asset('js/admin/checkboxes-permissions.js') }}"></script>
@endsection


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

{!! BootForm::hidden('activated')->value(0) !!}
{!! BootForm::checkbox(trans('validation.attributes.activated'), 'activated') !!}

@if ($roles = Roles::all() and $roles->count())
<div class="form-group">
    <label>@lang('validation.attributes.roles')</label>
    @foreach ($roles as $role)
    <div class="checkbox">
        <label>
            {!! BootForm::hidden('roles['.$role->id.']')->value(0) !!}
            <input type="checkbox" name="roles[{{ $role->id }}]" value="1" @if (isset($selectedRoles[$role->id]))checked="checked"@endif> {{ $role->name }}
        </label>
    </div>
    @endforeach
</div>
@endif

<label>@lang('users::global.User permissions')</label>
{!! BootForm::hidden('superuser')->value(0) !!}
{!! BootForm::checkbox(trans('validation.attributes.superuser'), 'superuser') !!}

@include('core::admin._permissions-form')
