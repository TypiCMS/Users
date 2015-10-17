<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use TypiCMS\Modules\Users\Http\Requests\FormRequestLogin;
use TypiCMS\Modules\Users\Models\User;

class AuthController extends Controller
{
    use ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('users::login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param FormRequestLogin $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(FormRequestLogin $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request);
        }

        $this->incrementLoginAttempts($request);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            $message = trans('users::global.User does not exist');
        } elseif (!$user->activated) {
            $message = trans('users::global.User not activated');
        } else {
            $message = trans('users::global.Wrong password, try again');
        }

        return redirect()
            ->route('login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $message,
            ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param bool                     $throttles
     *
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request)
    {
        $this->clearLoginAttempts($request);

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        return redirect()->intended(url('/'));
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the login credentials and requirements.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'activated' => 1,
        ];
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }
}
