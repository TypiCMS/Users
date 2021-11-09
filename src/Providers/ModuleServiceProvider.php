<?php

namespace TypiCMS\Modules\Users\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Users\Composers\SidebarViewComposer;
use TypiCMS\Modules\Users\Facades\Users;
use TypiCMS\Modules\Users\Models\User;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.users');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');

        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'users');

        $this->publishes([
            __DIR__.'/../../database/migrations/create_users_table.php.stub' => getMigrationFileName('create_users_table'),
            __DIR__.'/../../database/migrations/create_password_resets_table.php.stub' => getMigrationFileName('create_password_resets_table'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/users'),
        ], 'views');

        AliasLoader::getInstance()->alias('Users', Users::class);

        View::composer('core::admin._sidebar', SidebarViewComposer::class);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind('Users', User::class);
    }
}
