<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use App;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use Input;
use Mail;
use Redirect;
use TypiCMS\Http\Controllers\AdminSimpleController;
use TypiCMS\Modules\Users\Http\Requests\FormRequest;
use TypiCMS\Modules\Users\Http\Requests\FormRequestChangePassword;
use TypiCMS\Modules\Users\Http\Requests\FormRequestResetPassword;
use TypiCMS\Modules\Users\Repositories\UserInterface;
use View;

class AdminController extends AdminSimpleController
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
    public function store(FormRequest $request)
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

    public function getLogin()
    {
        return view('users::login');
    }

    public function postLogin()
    {
        $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password')
        );

        try {
            $user = $this->repository->authenticate($credentials, false);
            return Redirect::intended('/');
        } catch (Exception $e) {
            return Redirect::route('login')
                ->withInput()
                ->with('message', $e->getMessage());
        }
    }

    public function getLogout()
    {
        $this->repository->logout();
        return Redirect::back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
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
    public function edit($model)
    {
        $selectedGroups = $this->repository->getGroups($model);
        $permissions = $model->getPermissions();
        return view('core::admin.edit')
            ->with(compact('model', 'selectedGroups', 'permissions'));
    }

    /**
     * Get registration form.
     *
     * @return Response
     */
    public function getRegister()
    {
        // Show the register form
        return view('users::register');
    }

    /**
     * Register a new user.
     *
     * @param  FormRequest $request
     * @return Response
     */
    public function postRegister(FormRequest $request)
    {
        // confirmation
        $activate = false;

        try {

            $input = $request->except('password_confirmation');
            $this->repository->register($input, $activate);
            $message = 'Your account has been created, ';
            $message .= $activate ? 'you can now log in' : 'check your email for the confirmation link' ;
            return Redirect::route('login')
                ->with('message', trans('users::global.' . $message) . '.');

        } catch (Exception $e) {
            return Redirect::route('register')
                ->with('message', $e->getMessage())
                ->withInput();
        }

    }

    /**
     * Activate a new User
     * 
     * @param  $id       user id
     * @param  $code
     * @return Redirect
     */
    public function getActivate($id = null, $code = null)
    {
        try {
            $this->repository->activate($id, $code);
            $message = trans('users::global.Your account has been activated, you can now log in') . '.';
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return Redirect::route('login')
            ->with('message', $message);

    }

    /**
     * Forgot Password / Reset
     */
    public function getResetpassword()
    {
        // Show the reset password form
        return view('users::reset-password');
    }

    /**
     * Reset password
     * 
     * @param  FormRequestResetPassword $request
     * @return Redirect
     */
    public function postResetpassword(FormRequestResetPassword $request)
    {
        try {
            $email = Input::get('email');
            $user = $this->repository->findUserByLogin($email);
            $data = array();
            $data['code'] = $this->repository->getResetPasswordCode($user);
            $data['id'] = $this->repository->getId($user);
            $data['email'] = $email;

            // Email the reset code to the user
            Mail::send('users::emails.reset', $data, function (Message $message) use ($data) {
                $subject  = '[' . config('typicms.' . App::getLocale() . '.website_title') . '] ';
                $subject .= trans('users::global.Password Reset Confirmation');
                $message->to($data['email'])->subject($subject);
            });

            $message = trans('users::global.An email was sent with password reset information') . '.';

            return Redirect::route('login')
                ->with('message', $message);

        } catch (Exception $e) {

            return Redirect::route('resetpassword')
                ->withInput()
                ->with('message', $e->getMessage());
        }

    }

    /**
     * Change User's password view
     * 
     * @param  $id         the user id
     * @param  $code
     * @return mixed
     */
    public function getChangepassword($id = null, $code = null)
    {
        try {
            // Find the user
            $user = $this->repository->byId($id);
            if (! $this->repository->checkResetPasswordCode($user, $code)) {
                $message = trans('users::global.This password reset token is invalid') . '.';
                return Redirect::route('login')
                    ->with(compact('message'));
            }

            return view('users::change-password')
                ->with(compact('id', 'code'));

        } catch (Exception $e) {
            $message = trans('users::global.User does not exist') . '.';
            return Redirect::route('login')
                ->with(compact('message'));
        }

    }

    /**
     * Change User's password
     * 
     * @param  FormRequestChangePassword $request
     * @return Redirect
     */
    public function postChangepassword(FormRequestChangePassword $request)
    {
        $input = $request->all();

        try {

            // Find the user
            $user = $this->repository->byId($input['id']);

            if ($this->repository->checkResetPasswordCode($user, $input['code'])) {
                // Attempt to reset the user password
                if ($this->repository->attemptResetPassword($user, $input['code'], $input['password'])) {

                    $message = trans('users::global.Your password has been changed') . '.';
                    return Redirect::route('login')
                        ->with(compact('message'));

                } else {
                    // Password reset failed
                    $message = trans('users::global.There was a problem, please contact the system administrator') . '.';
                }
            } else {
                $message = trans('users::global.This password reset token is invalid') . '.';
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return Redirect::route('login')
            ->with(compact('message'));

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
