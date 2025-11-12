<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Database
 */
class Database extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'databases';

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
        'description',
        'entity_resp_id',
        'responsible',
        'type',
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
