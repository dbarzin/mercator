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
 * App\Entity
 */
class Entity extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'entities';

    public static $searchable = [
        'name',
        'description',
        'security_level',
        'contact_point',
        'entity_type',
    ];

    protected $dates = [
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

    public function databases(): HasMany
    {
        return $this->hasMany(Database::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function respApplications(): HasMany
    {
        return $this->hasMany(MApplication::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function sourceRelations(): HasMany
    {
        return $this->hasMany(Relation::class, 'source_id', 'id')->orderBy('name');
    }

    public function destinationRelations(): HasMany
    {
        return $this->hasMany(Relation::class, 'destination_id', 'id')->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function parentEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_entity_id');
    }

    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class, 'parent_entity_id', 'id')->orderBy('name');
    }
}
