<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Input;
use Redirect;
use TypiCMS\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Http\Requests\FormRequestCreate;
use TypiCMS\Modules\Users\Repositories\UserInterface;

class AdminController extends BaseAdminController
{

    /**
     * __construct
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
     * @param  FormRequest $request
     * @return Redirect
     */
    public function store(FormRequestCreate $request)
    {
        $model = $this->repository->create($request->all());
        return $this->redirect($request, $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     * @param  FormRequest $request
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
     * @return Response
     */
    public function create($parent = null)
    {
        $model = $this->repository->getModel();
        $groups = $this->repository->getGroups();
        $selectedGroups = [];
        return view('core::admin.create')
            ->with(compact('model', 'groups', 'selectedGroups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $model
     * @return Response
     */
    public function edit($model, $child = null)
    {
        $selectedGroups = $this->repository->getGroups($model);
        $permissions = $model->getPermissions();
        return view('core::admin.edit')
            ->with(compact('model', 'selectedGroups', 'permissions'));
    }

    /**
     * Update User's preferences
     *
     * @return void
     */
    public function postUpdatePreferences()
    {
        $input = Input::all();
        $this->repository->updatePreferences($input);
    }
}
