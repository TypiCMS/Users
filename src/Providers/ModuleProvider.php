<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Observers\FileObserver;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\EloquentUser;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.users'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['users' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'users');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'users');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/users'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Users',
            'TypiCMS\Modules\Users\Facades\Facade'
        );

        // Observers
        User::observe(new FileObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Users\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Users\Composers\SidebarViewComposer');

        $app->bind('TypiCMS\Modules\Users\Repositories\UserInterface', function (Application $app) {
            return new EloquentUser(new User());
        });
    }
}
