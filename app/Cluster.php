<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cluster
 */
class Cluster extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'clusters';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'address_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function logicalServers()
    {
        return $this->hasMany(LogicalServer::class, 'cluster_id')->orderBy('name');
    }

    public function physicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'cluster_id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
