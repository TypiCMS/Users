<?php
namespace TypiCMS\Modules\Users\Providers;

use Config;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

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
     * @param  \Illuminate\Routing\Router  $router
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
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function($router) {
            /**
             * Front office routes
             */

            // Registration
            $router->group(['before' => 'visitor.mayRegister'], function ($router) {
                $router->get('users/register', array('as' => 'register', 'uses' => 'AuthController@getRegister'));
                $router->post('users/register', 'AuthController@postRegister');
            });

            // Login
            $router->get('users/login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
            $router->post('users/login', 'AuthController@postLogin');

            // Logout
            $router->get('users/logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);

            // Activation
            $router->get(
                'users/activate/{userid}/{activationCode}',
                ['as' => 'activate', 'uses' => 'AuthController@getActivate']
            );

            // Request new password
            $router->get(
                'users/resetpassword',
                ['as' => 'resetpassword', 'uses' => 'AuthController@getResetpassword']
            );
            $router->post('users/resetpassword', 'AuthController@postResetpassword');

            // Set new password
            $router->get(
                'users/changepassword/{id}/{code}',
                ['as' => 'changepassword', 'uses' => 'AuthController@getChangepassword']
            );
            $router->post('users/changepassword/{id}/{code}', 'AuthController@postChangepassword');

            /**
             * Admin routes
             */
            $router->resource('admin/users', 'AdminController');
            $router->post('admin/users/current/updatepreferences', 'AdminController@postUpdatePreferences');

            /**
             * API routes
             */
            $router->resource('api/users', 'ApiController');
        });
    }

}
