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
     * Create a new model
     *
     * @param  array $data
     * @return mixed Model or false on error during save
     */
    public function create(array $data)
    {
        $userData = array_except($data, ['_method','_token', 'id', 'exit', 'groups', 'password_confirmation']);
        $userData['password'] = bcrypt($data['password']);
        $userData['permissions'] = $this->permissions($data);

        foreach ($userData as $key => $value) {
            $this->model->$key = $value;
        }

        if ($this->model->save()) {
            $this->syncGroups($this->model, $data);
            return $this->model;
        }

        return false;
    }

    /**
     * Update an existing model
     *
     * @param  array  $data
     * @return boolean
     */
    public function update(array $data)
    {
        $user = $this->model->find($data['id']);

        $userData = array_except($data, ['_method', '_token', 'exit', 'groups', 'password_confirmation']);
        $userData['permissions'] = $this->permissions($data);

        if (! $userData['password']) {
            $userData = array_except($userData, 'password');
        } else {
            $userData['password'] = bcrypt($data['password']);
        }

        foreach ($userData as $key => $value) {
            $user->$key = $value;
        }

        $this->syncGroups($user, $data);

        if ($user->save()) {
            return true;
        }

        return false;

    }

    /**
     * Find user by token
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
     * Sync groups
     *
     * @param  Model $user
     * @param  array $groups
     * @return void
     */
    private function syncGroups($user, $data)
    {
        if (! isset($data['groups'])) {
            return;
        }
        $array = [];
        foreach ($data['groups'] as $id => $value) {
            if ($value) {
                $array[] = $id;
            }
        }
        $user->groups()->sync($array);
    }

    /**
     * get extract and encode permissions from array
     *
     * @param  array $data
     * @return string|null
     */
    private function permissions($data)
    {
        if (isset($data['permissions'])) {
            return json_encode($data['permissions']);
        }
        return null;
    }
    /**
     * Update current user preferences
     *
     * @return mixed
     */
    public function updatePreferences(array $data)
    {
        $user = $this->model;

        // get preferences of current user
        $prefs = $user->preferences;

        // convert to array
        $prefsArray = (array) json_decode($prefs, true);

        // add data
        $prefsArray = array_merge($prefsArray, $data);

        // convert to json
        $prefs = json_encode($prefsArray);

        // save preferences
        $user->preferences = $prefs;
        $user->save();
    }

    /**
     * Get current user preferences
     *
     * @return array
     */
    public function getPreferences()
    {
        if ($this->model) {
            return json_decode($this->model->preferences, true);
        }
        return [];
    }

    /**
     * Current user has access ?
     *
     * @param  string|array  $permissions
     * @return bool
     */
    public function hasAccess($permissions)
    {
        if ($user = Request::user()) {
            return $user->hasAccess($permissions);
        }
        return false;
    }
}
