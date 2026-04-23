<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Factories\ClusterFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cluster
 */
class Cluster extends Model implements HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'clusters';

    public static string $prefix = 'CLUST_';

    public static string $icon = '/images/cluster.png';

    public static array $searchable = [
        'name',
        'description',
        'type',
        'attributes',
        'address_ip',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'attributes',
        'icon_id',
        'description',
        'address_ip',
    ];

    protected static function newFactory(): Factory
    {
        return ClusterFactory::new();
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logicalServers(): BelongsToMany
    {
        return $this->BelongsToMany(LogicalServer::class)->orderBy('name');
    }

    /** @return BelongsToMany<Router, $this> */
    public function routers(): BelongsToMany
    {
        return $this->BelongsToMany(Router::class)->orderBy('name');
    }

    /** @return BelongsToMany<PhysicalServer, $this> */
    public function physicalServers(): BelongsToMany
    {
        return $this->BelongsToMany(PhysicalServer::class)->orderBy('name');
    }
}
