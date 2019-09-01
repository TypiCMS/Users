<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Users\Models\User;

class ApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $data = QueryBuilder::for(User::class)
            ->allowedFilters([
                Filter::custom('first_name,last_name,email', FilterOr::class),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        $user->preferences = array_merge((array) $user->preferences, request()->all());
        $user->save();
    }

    public function destroy(User $user)
    {
        $deleted = $user->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
