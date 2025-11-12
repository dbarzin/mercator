<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

class User extends Authenticatable implements LdapAuthenticatable
{
    use AuthenticatesWithLdap, HasApiTokens, HasFactory, HasLdapUser, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $hidden = ['remember_token', 'password'];

    // $dates est obsolète — préférez $casts
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = ['login', 'name', 'email', 'password', 'granularity', 'language'];

    /**
     * Mutator mot de passe : hash seulement si nécessaire.
     */
    protected function password(): Attribute
    {
        return Attribute::set(function (?string $value) {
            if ($value === null || $value === '') {
                return null;
            }

            return Hash::needsRehash($value) ? Hash::make($value) : $value;
        });
    }

    /**
     * Relation rôles
     *
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * L'utilisateur es-til administrateur ?
     */
    public function isAdmin(): bool
    {
        return $this->roles()->whereKey(1)->exists();
    }

    /**
     * Ajouter un rôle (utilise attach sur pivot).
     */
    public function addRole(Role $role): void
    {
        if (! $this->hasRole($role)) {
            $this->roles()->attach($role->getKey());
            // Optionnel : tenir le cache en mémoire si déjà chargé :
            if ($this->relationLoaded('roles')) {
                $this->setRelation('roles', $this->getRelation('roles')->push($role));
            }
        }
    }

    /**
     * Vérifie si l'utilisateur possède un rôle.
     *
     * @param  string|Role  $role  Titre/slug OU instance Role.
     */
    public function hasRole(string|Role $role): bool
    {
        // Zéro requête si déjà eager-loaded
        if ($this->relationLoaded('roles')) {
            $roles = $this->getRelation('roles'); // Collection<Role>

            return $role instanceof Role
                ? $roles->contains('id', $role->getKey())
                : ($roles->contains('slug', $role) || $roles->contains('title', $role));
        }

        // Requête minimale
        if ($role instanceof Role) {
            return $this->roles()->whereKey($role->getKey())->exists();
        }

        // Privilégie 'slug' si disponible
        return $this->roles()
            ->where(fn ($q) => $q->where('slug', $role)->orWhere('title', $role))
            ->exists();
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function m_applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class, 'cartographer_m_application');
    }
}
