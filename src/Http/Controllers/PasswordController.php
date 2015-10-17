<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Users\Http\Requests\FormRequestEmail;
use TypiCMS\Modules\Users\Http\Requests\FormRequestPassword;

class PasswordController extends Controller
{
    /**
     * Create a new password controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard          $auth
     * @param \Illuminate\Contracts\Auth\PasswordBroker $passwords
     *
     * @return void
     */
    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;

        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function getEmail()
    {
        return view('users::password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param FormRequestEmail $request
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function postEmail(FormRequestEmail $request)
    {
        $response = $this->passwords->sendResetLink($request->only('email'), function (Message $message) {
            $subject = '['.TypiCMS::title().'] '.trans('users::global.Your Password Reset Link');
            $message->subject($subject);
        });

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case PasswordBroker::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param string $token
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        return view('users::reset')->with(compact('token'));
    }

    /**
     * Reset the given user's password.
     *
     * @param FormRequestPassword $request
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function postReset(FormRequestPassword $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);

            $user->save();

            if ($user->activated) {
                $this->auth->login($user);
            }
        });

        switch ($response) {
            case PasswordBroker::PASSWORD_RESET:
                return redirect(route('login'));

            default:
                return redirect()->back()
                            ->withInput($request->only('email'))
                            ->withErrors(['email' => trans($response)]);
        }
    }
}
