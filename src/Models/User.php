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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'preferences' => 'array',
    ];

    /**
     * Get front office uri.
     *
     * @param string $locale
     *
     * @return string
     */
    public function uri($locale = null)
    {
        return '/';
    }

    /**
     * Get back office’s edit url of model.
     *
     * @return string|void
     */
    public function editUrl()
    {
        $route = 'admin::edit-'.Str::singular($this->getTable());
        if (Route::has($route)) {
            return route($route, $this->id);
        }

        return route('dashboard');
    }

    /**
     * Get back office’s index of models url.
     *
     * @return string|void
     */
    public function indexUrl()
    {
        $route = 'admin::index-'.$this->getTable();
        if (Route::has($route)) {
            return route($route);
        }

        return route('dashboard');
    }

    /**
     * Boot the model.
     *
     * @return null
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->api_token = Str::uuid();
        });
    }

    /**
     * Check if the user is a superuser.
     *
     * @return bool
     */
    public function isSuperUser()
    {
        return (bool) $this->superuser;
    }

    /**
     * Sync permissions.
     *
     * @param array $permissions
     *
     * @return null
     */
    public function syncPermissions(array $permissions)
    {
        $permissionIds = [];
        foreach ($permissions as $name) {
            $permissionIds[] = app(Permission::class)->firstOrCreate(['name' => $name])->id;
        }
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}
