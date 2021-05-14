<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Database extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'databases';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'responsible',
        'type',
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
        'external',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function databaseSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'database_source_id', 'id');
    }

    public function databaseDestFluxes()
    {
        return $this->hasMany(Flux::class, 'database_dest_id', 'id');
    }

    public function databasesMApplications()
    {
        return $this->belongsToMany(MApplication::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class);
    }

    public function entity_resp()
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    public function informations()
    {
        return $this->belongsToMany(Information::class);
    }
}
