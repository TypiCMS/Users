<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
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
     * @param  FormRequest $request
     * @return Model|false
     */
    public function store(FormRequest $request)
    {
        $model = $this->repository->create(Input::all());
        $error = $model ? false : true ;
        return response()->json([
            'error' => $error,
            'model' => $model,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     * @param  FormRequest $request
     * @return boolean
     */
    public function update($model, FormRequest $request)
    {
        $error = $this->repository->update($request->all()) ? false : true ;
        return response()->json([
            'error' => $error,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $model
     * @return \Illuminate\Support\Facades\Response
     */
    public function destroy($model)
    {
        if ($model->id == Auth::user()->id) {
            return response()->json([
                'error'   => true,
                'message' => 'Connected user can not be deleted.'
            ], 403);
        }
        return parent::destroy($model);
    }
}
