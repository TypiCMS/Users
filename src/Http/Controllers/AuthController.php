<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use TypiCMS\Modules\Users\Http\Requests\FormRequestLogin;
use TypiCMS\Modules\Users\Models\User;

class AuthController extends Controller
{

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
     * @param  FormRequestLogin $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(FormRequestLogin $request)
    {
        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->intended(url('/'));
        }

        $user = User::where('email', $credentials['email'])->first();
        if (! $user) {
            $message = trans('users::global.User does not exist');
        } elseif (! $user->activated) {
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
     * @param  Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'activated' => 1
        ];
    }

}
