<?php
namespace TypiCMS\Modules\Users\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;

class User extends Base implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable;
    use CanResetPassword;
    use Historable;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Users\Presenters\ModulePresenter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'permissions',
        'preferences',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get front office uri
     *
     * @param  string $locale
     * @return null
     */
    public function uri($locale)
    {
        return null;
    }

    /**
     * Current user has access ?
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     */
    public function hasAccess($permissions, $all = true)
    {
        return true;
    }

    /**
     * Set the password attribute.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Confirm the user.
     *
     * @return void
     */
    public function confirmEmail()
    {
        $this->activated = true;
        $this->token = null;
        $this->save();
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->token = str_random(30);
        });
    }

    /**
     * One user has many groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->belongsToMany('TypiCMS\Modules\Groups\Models\Group');
    }

    /**
     * Mutator for giving permissions.
     *
     * @param  mixed  $permissions
     * @return array  $_permissions
     */
    public function getPermissionsAttribute($permissions)
    {
        if (! $permissions) {
            return [];
        }
        if (is_array($permissions)) {
            return $permissions;
        }
        return json_decode($permissions, true);
    }
}
