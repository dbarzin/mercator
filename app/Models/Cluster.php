<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cluster
 */
class Cluster extends Model
{
    use Auditable, SoftDeletes;

    public $table = 'clusters';

    public static array $searchable = [
        'name',
        'description',
        'type',
        'attributes'
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
        'description',
        'icon_id',
        'address_ip',
    ];

    public function logicalServers(): BelongsToMany
    {
        return $this->BelongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function routers(): BelongsToMany
    {
        return $this->BelongsToMany(Router::class)->orderBy('name');
    }

    public function physicalServers(): BelongsToMany
    {
        return $this->BelongsToMany(PhysicalServer::class)->orderBy('name');
    }
}
