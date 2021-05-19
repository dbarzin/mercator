<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Network extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'networks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
    ];

    protected $fillable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
        'security_need',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function connectedNetworksExternalConnectedEntities()
    {
        return $this->belongsToMany(ExternalConnectedEntity::class);
    }

    public function subnetworks()
    {
        return $this->belongsToMany(Subnetwork::class);
    }
}
