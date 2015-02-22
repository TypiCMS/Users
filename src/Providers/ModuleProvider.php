<?php
namespace TypiCMS\Modules\Users\Providers;

use App;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\SentryUser;
use TypiCMS\Observers\FileObserver;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.users'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'users');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'users');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/users'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../database' => base_path('database'),
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../../tests' => base_path('tests'),
        ], 'tests');

        // Add user preferences to Config
        $prefs = App::make('TypiCMS\Modules\Users\Repositories\UserInterface')->getPreferences();
        Config::set('typicms.user', $prefs);

        AliasLoader::getInstance()->alias(
            'Users',
            'TypiCMS\Modules\Users\Facades\Facade'
        );

        // Observers
        User::observe(new FileObserver);
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Users\Providers\RouteServiceProvider');

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Users\Composers\SideBarViewComposer');

        $app->bind('TypiCMS\Modules\Users\Repositories\UserInterface', function (Application $app) {
            return new SentryUser(
                $app['sentry']
            );
        });

    }
}
