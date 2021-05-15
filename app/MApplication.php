<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class MApplication extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'm_applications';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'responsible',
    ];

    protected $fillable = [
        'name',
        'description',
        'entity_resp_id',
        'responsible',
        'technology',
        'documentation',
        'type',
        'users',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'external',
        'application_block_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function applicationSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'application_source_id', 'id');
    }

    public function applicationDestFluxes()
    {
        return $this->hasMany(Flux::class, 'application_dest_id', 'id');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class);
    }

    public function entity_resp()
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class);
    }

    public function services()
    {
        return $this->belongsToMany(ApplicationService::class);
    }

    public function databases()
    {
        return $this->belongsToMany(Database::class);
    }

    public function logical_servers()
    {
        return $this->belongsToMany(LogicalServer::class);
    }

    public function application_block()
    {
        return $this->belongsTo(ApplicationBlock::class, 'application_block_id');
    }
}
