<?php

namespace TypiCMS\Modules\Users\Repositories;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;
use TypiCMS\Modules\Users\Models\User;

class EloquentUser extends RepositoriesAbstract implements UserInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new model.
     *
     * @param array $data
     *
     * @return mixed Model or false on error during save
     */
    public function create(array $data)
    {
        $userData = array_except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);
        $userData['password'] = bcrypt($data['password']);

        $user = $this->model->fill($userData);

        if ($user->save()) {
            $roles = isset($data['roles']) ? $data['roles'] : [];
            $permissions = isset($data['permissions']) ? $data['permissions'] : [];
            $user->roles()->sync($roles);
            $user->syncPermissions($permissions);

            return $user;
        }

        return false;
    }

    /**
     * Update an existing model.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $user = $this->model->find($data['id']);

        $userData = array_except($data, ['exit', 'permissions', 'roles', 'password_confirmation']);

        if ($userData['password'] === '') {
            $userData = array_except($userData, 'password');
        } else {
            $userData['password'] = bcrypt($data['password']);
        }

        $user->fill($userData);

        $roles = isset($data['roles']) ? $data['roles'] : [];
        $permissions = isset($data['permissions']) ? $data['permissions'] : [];
        $user->roles()->sync($roles);
        $user->syncPermissions($permissions);

        if ($user->save()) {
            return true;
        }

        return false;
    }

    /**
     * Find user by token.
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function byToken($token)
    {
        return $this->model->where('token', $token)->first();
    }

    /**
     * Update current user preferences.
     *
     * @return mixed
     */
    public function updatePreferences(array $data)
    {
        $user = Request::user();
        $user->preferences = array_merge((array) $user->preferences, $data);
        $user->save();
    }
}
