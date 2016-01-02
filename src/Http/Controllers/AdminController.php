<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\UserInterface;

class AdminController extends BaseAdminController
{
    public function __construct(UserInterface $user)
    {
        parent::__construct($user);
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();
        $selectedGroups = [];

        return view('core::admin.create')
            ->with(compact('model', 'selectedGroups'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Users\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $permissions = $user->permissions;
        $selectedGroups = $user->groups->getDictionary();

        return view('core::admin.edit')
            ->with([
                'model'          => $user,
                'permissions'    => $permissions,
                'selectedGroups' => $selectedGroups,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Users\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $model = $this->repository->create($request->all());

        return $this->redirect($request, $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Users\Models\User               $user
     * @param \TypiCMS\Modules\Users\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $user);
    }

    /**
     * Update User's preferences.
     *
     * @return void
     */
    public function postUpdatePreferences()
    {
        $this->repository->updatePreferences(Request::all());
    }
}
