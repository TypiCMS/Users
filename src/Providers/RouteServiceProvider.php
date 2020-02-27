<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Users\Http\Controllers';

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Front office routes
             */
            $router->middleware('web')->group(function (Router $router) {
                foreach (locales() as $lang) {
                    if (config('typicms.register')) {
                        // Registration
                        $router->get($lang.'/register', 'RegisterController@showRegistrationForm')->name($lang.'::register');
                        $router->post($lang.'/register', 'RegisterController@register');
                        // Verify
                        $router->get($lang.'/email/verify', 'VerificationController@show')->name($lang.'::verification.notice');
                        $router->get($lang.'/email/verify/{id}', 'VerificationController@verify')->name($lang.'::verification.verify');
                        $router->get($lang.'/email/resend', 'VerificationController@resend')->name($lang.'::verification.resend');
                    }
                    // Login
                    $router->get($lang.'/login', 'LoginController@showLoginForm')->name($lang.'::login');
                    $router->post($lang.'/login', 'LoginController@login');
                    // Request new password
                    $router->get($lang.'/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name($lang.'::password.request');
                    $router->post($lang.'/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name($lang.'::password.email');
                    // Set new password
                    $router->get($lang.'/password/reset/{token}', 'ResetPasswordController@showResetForm')->name($lang.'::password.reset');
                    $router->post($lang.'/password/reset', 'ResetPasswordController@reset');
                    // Logout
                    $router->post($lang.'/logout', 'LoginController@logout')->name($lang.'::logout');
                }
            });

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('users', 'AdminController@index')->name('admin::index-users')->middleware('can:see-all-users');
                $router->get('users/create', 'AdminController@create')->name('admin::create-user')->middleware('can:create-user');
                $router->get('users/{user}/edit', 'AdminController@edit')->name('admin::edit-user')->middleware('can:update-user');
                $router->post('users', 'AdminController@store')->name('admin::store-user')->middleware('can:create-user');
                $router->put('users/{user}', 'AdminController@update')->name('admin::update-user')->middleware('can:update-user');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('users', 'ApiController@index')->middleware('can:see-all-users');
                    $router->post('users/current/updatepreferences', 'ApiController@updatePreferences')->middleware('can:update-preferences');
                    $router->patch('users/{user}', 'ApiController@updatePartial')->middleware('can:update-user');
                    $router->delete('users/{user}', 'ApiController@destroy')->middleware('can:delete-user');
                });
            });
        });
    }
}
