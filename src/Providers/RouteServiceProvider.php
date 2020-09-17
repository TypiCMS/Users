<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Http\Middleware\SetLocale;
use TypiCMS\Modules\Users\Http\Controllers\AdminController;
use TypiCMS\Modules\Users\Http\Controllers\ApiController;
use TypiCMS\Modules\Users\Http\Controllers\ForgotPasswordController;
use TypiCMS\Modules\Users\Http\Controllers\LoginController;
use TypiCMS\Modules\Users\Http\Controllers\RegisterController;
use TypiCMS\Modules\Users\Http\Controllers\ResetPasswordController;
use TypiCMS\Modules\Users\Http\Controllers\VerificationController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Front office routes
             */
            $router->middleware('web', SetLocale::class)->group(function (Router $router) {
                foreach (locales() as $lang) {
                    if (config('typicms.register')) {
                        // Registration
                        $router->get($lang.'/register', [RegisterController::class, 'showRegistrationForm'])->name($lang.'::register');
                        $router->post($lang.'/register', [RegisterController::class, 'register']);
                        // Verify
                        $router->get($lang.'/email/verify', [VerificationController::class, 'show'])->name($lang.'::verification.notice');
                        $router->get($lang.'/email/verify/{id}', [VerificationController::class, 'verify'])->name($lang.'::verification.verify');
                        $router->get($lang.'/email/resend', [VerificationController::class, 'resend'])->name($lang.'::verification.resend');
                    }
                    // Login
                    $router->get($lang.'/login', [LoginController::class, 'showLoginForm'])->name($lang.'::login');
                    $router->post($lang.'/login', [LoginController::class, 'login']);
                    // Request new password
                    $router->get($lang.'/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name($lang.'::password.request');
                    $router->post($lang.'/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name($lang.'::password.email');
                    // Set new password
                    $router->get($lang.'/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name($lang.'::password.reset');
                    $router->post($lang.'/password/reset', [ResetPasswordController::class, 'reset']);
                    // Logout
                    $router->post($lang.'/logout', [LoginController::class, 'logout'])->name($lang.'::logout');
                }
            });

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('users', [AdminController::class, 'index'])->name('admin::index-users')->middleware('can:read users');
                $router->get('users/create', [AdminController::class, 'create'])->name('admin::create-user')->middleware('can:create users');
                $router->get('users/{user}/edit', [AdminController::class, 'edit'])->name('admin::edit-user')->middleware('can:update users');
                $router->post('users', [AdminController::class, 'store'])->name('admin::store-user')->middleware('can:create users');
                $router->put('users/{user}', [AdminController::class, 'update'])->name('admin::update-user')->middleware('can:update users');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('users', [ApiController::class, 'index'])->middleware('can:read users');
                    $router->post('users/current/updatepreferences', [ApiController::class, 'updatePreferences'])->middleware('can:update users');
                    $router->delete('users/{user}', [ApiController::class, 'destroy'])->middleware('can:delete users');
                });
            });
        });
    }
}
