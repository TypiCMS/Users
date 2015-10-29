<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
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
        $model = $this->repository->create(Input::all());
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
    public function update($model)
    {
        $error = $this->repository->update(Input::all()) ? false : true;

        return response()->json([
            'error' => $error,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $model
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function destroy($model)
    {
        if ($model->id == Auth::user()->id) {
            return response()->json([
                'error'   => true,
                'message' => 'Connected user can not be deleted.',
            ], 403);
        }

        return parent::destroy($model);
    }
}
