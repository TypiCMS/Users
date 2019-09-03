<?php

namespace TypiCMS\Modules\Users\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Traits\HasRoles;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Users\Notifications\ResetPassword;
use TypiCMS\Modules\Users\Notifications\VerifyEmail;
use TypiCMS\Modules\Users\Presenters\ModulePresenter;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmailContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasRoles;
    use Historable;
    use MustVerifyEmail;
    use Notifiable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'activated',
        'superuser',
        'api_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    public function uri($locale = null): string
    {
        return '/';
    }

    public function editUrl(): string
    {
        $route = 'admin::edit-'.Str::singular($this->getTable());
        if (Route::has($route)) {
            return route($route, $this->id);
        }

        return route('dashboard');
    }

    public function indexUrl(): string
    {
        $route = 'admin::index-'.$this->getTable();
        if (Route::has($route)) {
            return route($route);
        }

        return route('dashboard');
    }

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($user) {
            $user->api_token = Str::uuid();
        });
    }

    public function isSuperUser(): bool
    {
        return (bool) $this->superuser;
    }

    public function syncPermissions(array $permissions): void
    {
        $permissionIds = [];
        foreach ($permissions as $name) {
            $permissionIds[] = app(Permission::class)->firstOrCreate(['name' => $name])->id;
        }
        $this->permissions()->sync($permissionIds);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }
}
