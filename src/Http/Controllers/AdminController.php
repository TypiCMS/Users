<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\EloquentUser;

class AdminController extends BaseAdminController
{
    public function __construct(EloquentUser $user)
    {
        parent::__construct($user);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->findAll();
        app('JavaScript')->put('models', $models);

        return view('users::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->createModel();
        $model->permissions = [];
        $model->roles = [];

        return view('users::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Users\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $user->permissions = $user->permissions()->pluck('name')->all();
        $user->roles = $user->roles()->pluck('id')->all();

        return view('users::admin.edit')
            ->with(['model' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Users\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $data = $request->all();

        $userData = array_except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);
        $userData['password'] = Hash::make($data['password']);

        $user = $this->repository->create($userData);

        if ($user) {
            $roles = $data['roles'] ?? [];
            $user->roles()->sync($roles);
            $permissions = $data['permissions'] ?? [];
            $user->syncPermissions($permissions);
        }

        return $this->redirect($request, $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Users\Models\User               $user
     * @param \TypiCMS\Modules\Users\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, FormRequest $request)
    {
        $data = $request->all();

        $userData = array_except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);

        if (!isset($userData['password']) || $userData['password'] === '') {
            $userData = array_except($userData, 'password');
        } else {
            $userData['password'] = Hash::make($data['password']);
        }

        $roles = $data['roles'] ?? [];
        $permissions = $data['permissions'] ?? [];
        $user->roles()->sync($roles);
        $user->syncPermissions($permissions);

        $this->repository->update($user->id, $userData);

        return $this->redirect($request, $user);
    }

    /**
     * Update User's preferences.
     *
     * @return null
     */
    public function postUpdatePreferences()
    {
        $user = auth()->user();
        $user->preferences = array_merge((array) $user->preferences, request()->all());
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Users\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $deleted = $this->repository->delete($user);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
