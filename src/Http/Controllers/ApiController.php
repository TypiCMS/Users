<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Users\Repositories\UserInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
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
