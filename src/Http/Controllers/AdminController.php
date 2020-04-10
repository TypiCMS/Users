<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Roles\Models\Role;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;

class AdminController extends BaseAdminController
{
    private $userModel = null;

    public function __construct()
    {
        $this->userModel = app(config('auth.providers.users.model'));
        parent::__construct();
    }

    public function index(): View
    {
        return view('users::admin.index');
    }

    public function create(): View
    {
        $model = $this->userModel;
        $model->permissions = [];
        $model->roles = [];
        $roles = Role::get();

        return view('users::admin.create')
            ->with(compact('model', 'roles'));
    }

    public function edit($userId): View
    {
        $user = $this->userModel->findOrFail($userId);
        $user->permissions = $user->permissions()->pluck('name')->all();
        $user->roles = $user->roles()->pluck('id')->all();
        $roles = Role::get();

        return view('users::admin.edit')
            ->with(['model' => $user, 'roles' => $roles]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $data = $request->except(['exit', 'permissions', 'roles', 'password', 'password_confirmation']);
        $data['password'] = Hash::make($request->input('password'));
        $data['email_verified_at'] = Carbon::now();
        $user = app(config('auth.providers.users.model'))->create($data);
        $user->roles()->sync($request->input('roles', []));

        return $this->redirect($request, $user);
    }

    public function update($userId, FormRequest $request): RedirectResponse
    {
        $user = $this->userModel->findOrFail($userId);
        $data = $request->except(['exit', 'permissions', 'roles', 'password', 'password_confirmation']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        $user->update($data);
        $user->roles()->sync($request->input('roles', []));
        (new Role())->flushCache();

        return $this->redirect($request, $user);
    }
}
