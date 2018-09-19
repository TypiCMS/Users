<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\EloquentUser;

class ApiController extends BaseApiController
{
    public function __construct(EloquentUser $user)
    {
        parent::__construct($user);
    }

    public function index(Request $request)
    {
        $models = QueryBuilder::for(User::class)
            ->paginate($request->input('per_page'));

        return $models;
    }

    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        $user->preferences = array_merge((array) $user->preferences, request()->all());
        $user->save();
    }

    public function destroy(User $user)
    {
        $deleted = $this->repository->delete($user);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
