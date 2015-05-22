<?php
namespace TypiCMS\Modules\Users\Repositories;

use TypiCMS\Modules\Core\Repositories\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{

    /**
     * Create a new model
     *
     * @param  array $data
     * @return mixed Model or false on error during save
     */
    public function create(array $data);

    /**
     * Update an existing model
     *
     * @param  array  $data
     * @return boolean
     */
    public function update(array $data);

    /**
     * Find user by token
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function byToken($token);

    /**
     * Update current user preferences
     *
     * @return mixed
     */
    public function updatePreferences(array $data);

    /**
     * Get current user preferences
     *
     * @return array
     */
    public function getPreferences();

    /**
     * Current user has access ?
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true);
}
