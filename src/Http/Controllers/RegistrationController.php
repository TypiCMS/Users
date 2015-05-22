<?php
namespace TypiCMS\Modules\Users\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Users\Http\Requests\FormRequestCreate;
use TypiCMS\Modules\Users\Repositories\UserInterface;

class RegistrationController extends Controller
{

    use ValidatesRequests;

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    protected $repository;

    /**
     * Create a new registration controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, UserInterface $user)
    {
        $this->auth = $auth;
        $this->repository = $user;

        $this->middleware('guest');
        $this->middleware('registration');
    }

    /**
     * Show the register page.
     *
     * @return \Response
     */
    public function getRegister()
    {
        return view('users::register');
    }

    /**
     * Perform the registration.
     *
     * @param  FormRequestCreate $request
     * @param  Mailer $mailer
     * @return \Redirect
     */
    public function postRegister(FormRequestCreate $request, Mailer $mailer)
    {
        $user = $this->repository->create($request->all());

        $mailer->send('users::emails.welcome', compact('user'), function (Message $message) use ($user) {
            $subject  = '[' . TypiCMS::title() . '] ' . trans('users::global.Welcome');
            $message->to($user->email)->subject($subject);
        });

        return redirect()
            ->back()
            ->with('status', trans('users::global.Your account has been created, check your email for the confirmation link'));
    }

    /**
     * Confirm a user’s email address.
     *
     * @param  string $token
     * @return mixed
     */
    public function confirmEmail($token)
    {
        $user = $this->repository->getFirstBy('token', $token);

        if (! $user) {
            abort(404);
        }

        $user->confirmEmail();

        return redirect()
            ->route('login')
            ->with('status', trans('users::global.Your account has been activated, you can now log in'));
    }
}
