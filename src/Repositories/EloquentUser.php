<?php
namespace TypiCMS\Modules\Users\Repositories;

use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

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
        foreach ($groups as $id => $value) {
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
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true)
    {
        if ($user = $this->model) {
            return $user->hasAccess($permissions, $all);
        }
        return false;
    }
}
