@extends('core::admin.master')

@section('title', __('Users'))

@section('content')

<item-list
    url-base="/api/users"
    locale="{{ config('typicms.content_locale') }}"
    fields="id,first_name,last_name,email,activated,superuser"
    table="users"
    title="users"
    :publishable="false"
    :searchable="['first_name,last_name,email']"
    :sorting="['first_name']">

    <template slot="add-button">
        @include('core::admin._button-create', ['module' => 'users'])
    </template>

    <template slot="columns" slot-scope="{ sortArray }">
        <item-list-column-header name="checkbox"></item-list-column-header>
        <item-list-column-header name="edit"></item-list-column-header>
        <item-list-column-header name="first_name" sortable :sort-array="sortArray" :label="$t('First name')"></item-list-column-header>
        <item-list-column-header name="last_name" sortable :sort-array="sortArray" :label="$t('Last name')"></item-list-column-header>
        <item-list-column-header name="email" sortable :sort-array="sortArray" :label="$t('Email')"></item-list-column-header>
        <item-list-column-header name="activated" sortable :sort-array="sortArray" :label="$t('Activated')"></item-list-column-header>
        @if (auth()->user()->isSuperUser())
        <item-list-column-header name="superuser" sortable :sort-array="sortArray" :label="$t('Superuser')"></item-list-column-header>
        @endif
    </template>

    <template slot="table-row" slot-scope="{ model, checkedModels, loading }">
        <td class="checkbox"><item-list-checkbox :model="model" :checked-models-prop="checkedModels" :loading="loading"></item-list-checkbox></td>
        <td>@include('core::admin._button-edit', ['module' => 'users'])</td>
        <td>@{{ model.first_name }}</td>
        <td>@{{ model.last_name }}</td>
        <td><a :href="'mailto:'+model.email">@{{ model.email }}</a></td>
        <td>
            <span class="badge badge-secondary" v-if="model.activated">@lang('Yes')</span>
            <span class="badge badge-light" v-else>@lang('No')</span>
        </td>
        @if (auth()->user()->isSuperUser())
        <td>
            <span class="badge badge-secondary" v-if="model.superuser">@lang('Yes')</span>
            <span class="badge badge-light" v-else>@lang('No')</span>
        </td>
        @endif
    </template>

</item-list>

@endsection
