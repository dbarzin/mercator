<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Network
 */
class Network extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'networks';

    public static $searchable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function externalConnectedEntities()
    {
        // return $this->belongsToMany(ExternalConnectedEntity::class)->orderBy('name');
        return $this->hasMany(ExternalConnectedEntity::class, 'network_id', 'id')->orderBy('name');
    }

    public function subnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'network_id', 'id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
