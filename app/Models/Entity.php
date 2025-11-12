<?php

namespace App\Models;

use App\Contracts\HasIcon;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entity
 */
class Entity extends Model implements HasIcon
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'entities';

    public static array $searchable = [
        'name',
        'description',
        'security_level',
        'contact_point',
        'entity_type',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'security_level',
        'contact_point',
        'is_external',
        'entity_type',
        'parent_entity_id',
    ];

    /*
     * Implement HasIcon
     */
    public function setIconId(?int $id): void
    {
        $this->icon_id = $id;
    }

    public function getIconId(): ?int
    {
        return $this->icon_id;
    }

    /** @return HasMany<Database, $this> */
    public function databases(): HasMany
    {
        return $this->hasMany(Database::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    /** @return HasMany<MApplication, $this> */
    public function respApplications(): HasMany
    {
        return $this->hasMany(MApplication::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Relation, $this> */
    public function sourceRelations(): HasMany
    {
        return $this->hasMany(Relation::class, 'source_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Relation, $this> */
    public function destinationRelations(): HasMany
    {
        return $this->hasMany(Relation::class, 'destination_id', 'id')->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    /** @return BelongsTo<Entity, $this> */
    public function parentEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_entity_id');
    }

    /** @return HasMany<Entity, $this> */
    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class, 'parent_entity_id', 'id')->orderBy('name');
    }
}
