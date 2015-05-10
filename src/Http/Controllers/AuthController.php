<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Users\Http\Requests\FormRequestChangePassword;
use TypiCMS\Modules\Users\Http\Requests\FormRequestRegister;
use TypiCMS\Modules\Users\Http\Requests\FormRequestResetPassword;
use TypiCMS\Modules\Users\Repositories\UserInterface;

class AuthController extends Controller
{

    protected $repository;

    public function __construct(UserInterface $user)
    {
        $this->repository = $user;
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
        return back();
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
     * @param  FormRequestRegister $request
     * @return Response
     */
    public function postRegister(FormRequestRegister $request)
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
                $subject  = '[' . TypiCMS::title() . '] ';
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
}
