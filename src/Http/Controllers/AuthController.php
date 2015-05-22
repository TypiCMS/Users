<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TypiCMS\Modules\Users\Http\Requests\FormRequestLogin;
use TypiCMS\Modules\Users\Services\Registrar;

class AuthController extends Controller
{

    use ValidatesRequests;

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \TypiCMS\Modules\Users\Services\Registrar  $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

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
     * @param  FormRequestLogin $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(FormRequestLogin $request)
    {
        $credentials = $this->getCredentials($request);

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            return redirect()->intended(url('/'));
        }

        return redirect()
            ->route('login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return trans('users::global.User not found');
    }

    /**
     * Get the login credentials and requirements.
     *
     * @param  Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'activated' => true
        ];
    }

}
