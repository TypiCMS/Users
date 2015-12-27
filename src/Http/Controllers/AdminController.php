<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Repositories\UserInterface;

class AdminController extends BaseAdminController
{
    /**
     * __construct.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        parent::__construct($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FormRequest $request
     *
     * @return Redirect
     */
    public function store(FormRequest $request)
    {
        $model = $this->repository->create($request->all());

        return $this->redirect($request, $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     * @param FormRequest $request
     *
     * @return Redirect
     */
    public function update($model, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function create($parent = null)
    {
        $model = $this->repository->getModel();
        $selectedGroups = [];

        return view('core::admin.create')
            ->with(compact('model', 'selectedGroups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $model
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function edit($model, $child = null)
    {
        $permissions = $model->permissions;
        $selectedGroups = $model->groups->getDictionary();

        return view('core::admin.edit')
            ->with(compact('model', 'permissions', 'selectedGroups'));
    }

    /**
     * Update User's preferences.
     *
     * @return void
     */
    public function postUpdatePreferences()
    {
        $input = Request::all();
        $this->repository->updatePreferences($input);
    }
}
