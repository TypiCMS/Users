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
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('users', 'TypiCMS\Modules\Users\Models\User');
    }

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
            $router->group(['prefix' => 'auth'], function (Router $router) {

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
            $router->resource('admin/users', 'AdminController');
            $router->post('admin/users/current/updatepreferences', ['as' => 'user.updatepreferences', 'uses' => 'AdminController@postUpdatePreferences']);

            /*
             * API routes
             */
            $router->resource('api/users', 'ApiController');
        });
    }
}
