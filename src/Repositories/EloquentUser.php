<?php

namespace TypiCMS\Modules\Users\Repositories;

use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\Users\Models\User;

class EloquentUser extends EloquentRepository
{
    protected $repositoryId = 'users';

    protected $model = User::class;

    /**
     * Find user by token.
     *
     * @param string $token
     */
    public function byToken($token)
    {
        return $this->createModel()->where('token', $token)->first();
    }
}
