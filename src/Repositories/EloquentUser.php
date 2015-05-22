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
        $userData['permissions'] = json_encode($data['permissions']);

        foreach ($userData as $key => $value) {
            $this->model->$key = $value;
        }

        if ($this->model->save()) {
            $this->sync($this->model, $data['groups']);
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
        $userData['permissions'] = json_encode($data['permissions']);

        if (! $userData['password']) {
            $userData = array_except($userData, 'password');
        }

        foreach ($userData as $key => $value) {
            $user->$key = $value;
        }

        $this->sync($user, $data['groups']);

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
    private function sync($user, $groups)
    {
        $array = [];
        foreach ($groups as $id => $value) {
            if ($value) {
                $array[] = $id;
            }
        }
        $user->groups()->sync($array);
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
