<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Subnetword extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'subnetworks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'address',
        'responsible_exp',
    ];

    protected $fillable = [
        'name',
        'description',
        'address',
        'ip_range',
        'ip_allocation_type',
        'responsible_exp',
        'dmz',
        'wifi', 
        'connected_subnets_id',
        'gateway_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function connectedSubnetsSubnetwords()
    {
        return $this->hasMany(Subnetword::class, 'connected_subnets_id', 'id');
    }

    public function subnetworksNetworks()
    {
        return $this->belongsToMany(Network::class);
    }

    public function connected_subnets()
    {
        return $this->belongsTo(Subnetword::class, 'connected_subnets_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }
}
