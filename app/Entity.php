<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entity
 */
class Entity extends Model
{
    use SoftDeletes, Auditable;

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

    public function databases()
    {
        return $this->hasMany(Database::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function applications()
    {
        return $this->hasMany(MApplication::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function sourceRelations()
    {
        return $this->hasMany(Relation::class, 'source_id', 'id')->orderBy('name');
    }

    public function destinationRelations()
    {
        return $this->hasMany(Relation::class, 'destination_id', 'id')->orderBy('name');
    }

    public function entitiesMApplications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function entitiesProcesses()
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function parentEntity()
    {
        return $this->belongsTo(Entity::class, 'parent_entity_id');
    }

    public function entities()
    {
        return $this->hasMany(Entity::class, 'parent_entity_id', 'id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
