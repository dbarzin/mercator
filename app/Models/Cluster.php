<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cluster
 */
class Cluster extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'clusters';

    public static array $searchable = [
        'name',
        'description',
        'type',
    ];

    protected array $dates = [
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

    public function logicalServers(): HasMany
    {
        return $this->hasMany(LogicalServer::class, 'cluster_id')->orderBy('name');
    }

    public function routers(): HasMany
    {
        return $this->hasMany(Router::class, 'cluster_id')->orderBy('name');
    }

    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'cluster_id')->orderBy('name');
    }
}
