<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    use HasFactory;
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
        'login',
        'name',
        'email',
        'password',
        'granularity',
        'language',
    ];

    // Add some caching for roles
    private ?BelongsToMany $cachedRoles = null;

    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Check if the User has the 'Admin' role, which is the first role in the app
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->id === 1) {
                return true;
            }
        }

        return false;
    }

    public function roles(): BelongsToMany
    {
        if ($this->cachedRoles === null) {
            $this->cachedRoles = $this->belongsToMany(Role::class);
        }

        return $this->cachedRoles;
    }

    /**
     * Permet d'ajouter un role à l'utilisateur courant
     */
    public function addRole(Role $role): void
    {
        if ($this->hasRole($role)) {
            return;
        }

        $this->roles()->save($role);
    }

    /**
     * Check si un utilisateur a un role
     *
     * @param  string|Role  $role
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

    public function m_applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class, 'cartographer_m_application');
    }
}
