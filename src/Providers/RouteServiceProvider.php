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
     *
     * @return null
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Front office routes
             */
            $router->group(['middleware' => 'web'], function (Router $router) {

                // Registration
                $router->get('register', 'RegisterController@showRegistrationForm')->name('register');
                $router->post('register', 'RegisterController@register');
                $router->get('activate/{token}', 'RegisterController@activate')->name('activate');

                // Login
                $router->get('login', 'LoginController@showLoginForm')->name('login');
                $router->post('login', 'LoginController@login');

                // Logout
                $router->get('logout', 'LoginController@logout')->name('logout');

                // Request new password
                $router->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('resetpassword');
                $router->post('password/email', 'ForgotPasswordController@sendResetLinkEmail');

                // Set new password
                $router->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('changepassword');
                $router->post('password/reset', 'ResetPasswordController@reset');
            });

            /*
             * Admin routes
             */
            $router->get('admin/users', 'AdminController@index')->name('admin::index-users');
            $router->get('admin/users/create', 'AdminController@create')->name('admin::create-user');
            $router->get('admin/users/{user}/edit', 'AdminController@edit')->name('admin::edit-user');
            $router->post('admin/users', 'AdminController@store')->name('admin::store-user');
            $router->put('admin/users/{user}', 'AdminController@update')->name('admin::update-user');
            $router->post('admin/users/current/updatepreferences', 'AdminController@postUpdatePreferences')->name('admin::update-preferences');

            /*
             * API routes
             */
            $router->get('api/users', 'ApiController@index')->name('api::index-users');
            $router->put('api/users/{user}', 'ApiController@update')->name('api::update-user');
            $router->delete('api/users/{user}', 'ApiController@destroy')->name('api::destroy-user');
        });
    }
}
