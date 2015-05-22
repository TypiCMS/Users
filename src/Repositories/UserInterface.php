<?php
namespace TypiCMS\Modules\Users\Repositories;

use TypiCMS\Modules\Core\Repositories\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{

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
