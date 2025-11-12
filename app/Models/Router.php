<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Router
 */
class Router extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'routers';

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
