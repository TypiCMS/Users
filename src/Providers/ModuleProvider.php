<?php
namespace TypiCMS\Modules\Users\Providers;

use App;
use Lang;
use View;
use Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

// Models
use TypiCMS\Modules\Users\Models\User;
use TypiCMS\Modules\Users\Repositories\SentryUser;

// Form
use TypiCMS\Modules\Users\Services\Form\UserForm;
use TypiCMS\Modules\Users\Services\Form\UserFormLaravelValidator;

// Observers
use TypiCMS\Observers\FileObserver;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'users');
        $this->publishes([
            __DIR__ . '/../config/' => config_path('typicms/users'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        // Add user preferences to Config
        $prefs = App::make('TypiCMS\Modules\Users\Repositories\UserInterface')->getPreferences();
        Config::set('current_user', $prefs);

        // Observers
        User::observe(new FileObserver);
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Users\Composers\SideBarViewComposer');

        $app->bind('TypiCMS\Modules\Users\Repositories\UserInterface', function (Application $app) {
            return new SentryUser(
                $app['sentry']
            );
        });

        $app->bind('TypiCMS\Modules\Users\Services\Form\UserForm', function (Application $app) {
            return new UserForm(
                new UserFormLaravelValidator($app['validator']),
                $app->make('TypiCMS\Modules\Users\Repositories\UserInterface')
            );
        });
    }
}
