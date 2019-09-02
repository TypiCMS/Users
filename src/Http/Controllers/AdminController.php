<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Models\User;

class AdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('users::admin.index');
    }

    public function create(): View
    {
        $model = new User();
        $model->permissions = [];
        $model->roles = [];

        return view('users::admin.create')
            ->with(compact('model'));
    }

    public function edit(User $user): View
    {
        $user->permissions = $user->permissions()->pluck('name')->all();
        $user->roles = $user->roles()->pluck('id')->all();

        return view('users::admin.edit')
            ->with(['model' => $user]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $data = $request->all();

        $userData = Arr::except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);
        $userData['password'] = Hash::make($data['password']);
        $userData['email_verified_at'] = Carbon::now();

        $user = User::create($userData);

        if ($user) {
            $roles = $data['roles'] ?? [];
            $user->roles()->sync($roles);
            $permissions = $data['permissions'] ?? [];
            $user->syncPermissions($permissions);
        }

        return $this->redirect($request, $user);
    }

    public function update(User $user, FormRequest $request): RedirectResponse
    {
        $data = $request->all();

        $userData = Arr::except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);

        if (!isset($userData['password']) || $userData['password'] === '') {
            $userData = Arr::except($userData, 'password');
        } else {
            $userData['password'] = Hash::make($data['password']);
        }

        $roles = $data['roles'] ?? [];
        $permissions = $data['permissions'] ?? [];
        $user->roles()->sync($roles);
        $user->syncPermissions($permissions);

        $user->update($userData);

        return $this->redirect($request, $user);
    }
}
