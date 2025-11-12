<?php

namespace App\Models;

use App\Contracts\HasIcon;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cluster
 */
class Cluster extends Model implements HasIcon
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'clusters';

    public static array $searchable = [
        'name',
        'description',
        'type',
        'attributes',
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

    /*
     * Implement HasIcon
     */
    public function setIconId(?int $id): void
    {
        $this->icon_id = $id;
    }

    public function getIconId(): ?int
    {
        return $this->icon_id;
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
