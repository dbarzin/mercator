<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Factories\DatabaseFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Database
 */
class Database extends Model implements HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'databases';

    public static string $prefix = 'DB_';

    public static string $icon = '/images/database.png';

    public static array $searchable = [
        'name',
        'description',
        'responsible',
        'type',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'icon_id',
        'description',
        'entity_resp_id',
        'responsible',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'external',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return DatabaseFactory::new();
    }

    /** @return HasMany<Flux, $this> */
    public function databaseSourceFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'database_source_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Flux, $this> */
    public function databaseDestFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'database_dest_id', 'id')->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Entity, $this> */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    /** @return BelongsTo<Entity, $this> */
    public function entity_resp(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    /** @return BelongsToMany<Information, $this> */
    public function informations(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logicalServers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    /** @return BelongsToMany<Container, $this> */
    public function containers(): BelongsToMany
    {
        return $this->belongsToMany(Container::class)->orderBy('name');
    }
}
