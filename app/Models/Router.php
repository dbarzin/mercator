<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\RouterFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Router
 */
class Router extends Model implements HasPrefix, HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'routers';

    public static string $prefix = 'LOG_ROUTER_';

    public static string $icon = '/images/router.png';

    public static array $searchable = [
        'name',
        'type',
        'description',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'rules',
        'ip_addresses',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected static function newFactory(): Factory
    {
        return RouterFactory::new();
    }

    /** @return BelongsToMany<PhysicalRouter, $this> */
    public function physicalRouters(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalRouter::class)->orderBy('name');
    }

    /** @return BelongsToMany<Cluster, $this> */
    public function clusters(): BelongsToMany
    {
        return $this->BelongsToMany(Cluster::class, 'cluster_id');
    }
}
