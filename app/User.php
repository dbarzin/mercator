<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

/**
 * App\User
 */
class User extends Authenticatable implements LdapAuthenticatable
{
    use AuthenticatesWithLdap;
    use HasApiTokens;
    use HasLdapUser;
    use Notifiable;
    use SoftDeletes;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected array $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'granularity',
        'language',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Check if the User has the 'Admin' role, which is the first role in the app
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->roles()->where('id', 1)->exists();
    }

    /**
     * Check si un utilisateur a un role
     *
     * @param String|Role $role
     *
     * @return bool
     */
    public function hasRole(mixed $role): bool
    {
        if ($role instanceof Role) {
            return $this->roles()->get()->contains($role);
        }
        if (is_string($role)) {
            return $this->roles()->get()->contains(Role::whereTitle($role)->first());
        }
        return false;
    }

    /**
     * Permet d'ajouter un role à l'utilisateur courant
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole(Role $role): void
    {
        if ($this->hasRole($role)) {
            return;
        }

        $this->roles()->save($role);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        if ($this->roles === null) {
            return $this->roles = $this->belongsToMany(Role::class)->orderBy('title');
        }
        return $this->roles;
    }

    public function m_applications()
    {
        return $this->belongsToMany(MApplication::class, 'cartographer_m_application');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
