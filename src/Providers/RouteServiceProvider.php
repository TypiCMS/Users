<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Http\Middleware\JavaScriptData;
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
    public function map()
    {
        /*
         * Front office routes
         */
        foreach (locales() as $lang) {
            Route::middleware('web', SetLocale::class, JavaScriptData::class)->prefix($lang)->name($lang.'::')->group(function (Router $router) {
                if (config('typicms.register')) {
                    // Registration
                    $router->get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
                    $router->post('register', [RegisterController::class, 'register']);
                    // Verify
                    $router->get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
                    $router->get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
                    $router->get('email/verified', [VerificationController::class, 'verified'])->name('verification.verified');
                    $router->get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
                }
                // Login
                $router->get('login', [LoginController::class, 'showLoginForm'])->name('login');
                $router->post('login', [LoginController::class, 'login']);
                // Request new password
                $router->get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
                $router->post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
                // Set new password
                $router->get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
                $router->post('password/reset', [ResetPasswordController::class, 'reset']);
                // Logout
                $router->post('logout', [LoginController::class, 'logout'])->name('logout');
            });
        }

        Route::redirect('/.well-known/change-password', '/'.app()->getLocale().'/password/reset');

        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('users', [AdminController::class, 'index'])->name('index-users')->middleware('can:read users');
            $router->get('users/export', [AdminController::class, 'export'])->name('export-users')->middleware('can:read users');
            $router->get('users/create', [AdminController::class, 'create'])->name('create-user')->middleware('can:create users');
            $router->get('users/{user}/edit', [AdminController::class, 'edit'])->name('edit-user')->middleware('can:read users');
            $router->post('users', [AdminController::class, 'store'])->name('store-user')->middleware('can:create users');
            $router->put('users/{user}', [AdminController::class, 'update'])->name('update-user')->middleware('can:update users');
        });

        /*
         * API routes
         */
        Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
            $router->get('users', [ApiController::class, 'index'])->middleware('can:read users');
            $router->post('users/current/updatepreferences', [ApiController::class, 'updatePreferences'])->middleware('can:update users');
            $router->delete('users/{user}', [ApiController::class, 'destroy'])->middleware('can:delete users');
        });
    }
}
