<?php

namespace TypiCMS\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use TypiCMS\Modules\Users\Http\Requests\FormRequestRegister;
use TypiCMS\Modules\Users\Mail\UserRegistered;
use TypiCMS\Modules\Users\Models\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('users::register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request                                 $request
     * @param \TypiCMS\Modules\Users\Http\Requests\FormRequestRegister $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(FormRequestRegister $request)
    {
        $user = $this->create($request->all());

        event(new Registered($user));

        Mail::to($user->email)->send(new UserRegistered($user));

        return redirect()
            ->back()
            ->with('status', trans('users::global.Your account has been created, check your email for the confirmation link'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * Confirm a user’s email address.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function activate($token)
    {
        $user = User::where('token', $token)->firstOrFail();

        $user->activate();

        return redirect()
            ->route('login')
            ->with('status', trans('users::global.Your account has been activated, you can now log in'));
    }
}
