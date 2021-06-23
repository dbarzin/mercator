<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Entity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'entities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'security_level',
        'contact_point',
    ];

    protected $fillable = [
        'name',
        'description',
        'security_level',
        'contact_point',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function entityRespDatabases()
    {
        return $this->hasMany(Database::class, 'entity_resp_id', 'id');
    }

    public function entityRespMApplications()
    {
        return $this->hasMany(MApplication::class, 'entity_resp_id', 'id');
    }

    public function sourceRelations()
    {
        return $this->hasMany(Relation::class, 'source_id', 'id');
    }

    public function destinationRelations()
    {
        return $this->hasMany(Relation::class, 'destination_id', 'id');
    }

    public function entitiesMApplications()
    {
        return $this->belongsToMany(MApplication::class);
    }

    public function entitiesProcesses()
    {
        return $this->belongsToMany(Process::class);
    }
}
