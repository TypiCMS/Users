<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\UserInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $model = $this->repository->create(Request::all());
        $error = $model ? false : true;

        return response()->json([
            'error' => $error,
            'model' => $model,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $updated = $this->repository->update(Request::all());

        return response()->json([
            'error' => !$updated,
        ]);
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
        if ($user->id == Auth::user()->id) {
            return response()->json([
                'error'   => true,
                'message' => 'Connected user can not be deleted.',
            ], 403);
        }

        $deleted = $this->repository->delete($user);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
