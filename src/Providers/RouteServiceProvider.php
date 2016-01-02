<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

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
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Front office routes
             */
            $router->group(['prefix' => 'auth', 'middleware' => 'web'], function (Router $router) {

                // Registration
                $router->get('register', ['as' => 'register', 'uses' => 'RegistrationController@getRegister']);
                $router->post('register', 'RegistrationController@postRegister');
                $router->get('activate/{token}', ['as' => 'activate', 'uses' => 'RegistrationController@confirmEmail']);

                // Login
                $router->get('login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
                $router->post('login', 'AuthController@postLogin');

                // Logout
                $router->get('logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);

                // Request new password
                $router->get(
                    'resetpassword',
                    ['as' => 'resetpassword', 'uses' => 'PasswordController@getEmail']
                );
                $router->post('resetpassword', 'PasswordController@postEmail');

                // Set new password
                $router->get(
                    'changepassword/{token}',
                    ['as' => 'changepassword', 'uses' => 'PasswordController@getReset']
                );
                $router->post('changepassword/{token}', 'PasswordController@postReset');
            });

            /*
             * Admin routes
             */
            $router->get('admin/users', ['as' => 'admin.users.index', 'uses' => 'AdminController@index']);
            $router->get('admin/users/create', ['as' => 'admin.users.create', 'uses' => 'AdminController@create']);
            $router->get('admin/users/{user}/edit', ['as' => 'admin.users.edit', 'uses' => 'AdminController@edit']);
            $router->post('admin/users', ['as' => 'admin.users.store', 'uses' => 'AdminController@store']);
            $router->put('admin/users/{user}', ['as' => 'admin.users.update', 'uses' => 'AdminController@update']);
            $router->post('admin/users/current/updatepreferences', ['as' => 'user.updatepreferences', 'uses' => 'AdminController@postUpdatePreferences']);

            /*
             * API routes
             */
            $router->get('api/users', ['as' => 'api.users.index', 'uses' => 'ApiController@index']);
            $router->put('api/users/{user}', ['as' => 'api.users.update', 'uses' => 'ApiController@update']);
            $router->delete('api/users/{user}', ['as' => 'api.users.destroy', 'uses' => 'ApiController@destroy']);
        });
    }
}
