<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\NetworkFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Network
 */
class Network extends Model implements HasPrefix, HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'networks';

    public static string $prefix = 'NETWORK_';

    public static string $icon = '/images/cloud.png';

    public static array $searchable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
    ];

    protected array $dates = [
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
        'security_need_auth',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return NetworkFactory::new();
    }

    /** @return HasMany<ExternalConnectedEntity, $this> */
    public function externalConnectedEntities(): HasMany
    {
        return $this->hasMany(ExternalConnectedEntity::class, 'network_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Subnetwork, $this> */
    public function subnetworks(): HasMany
    {
        return $this->hasMany(Subnetwork::class, 'network_id', 'id')->orderBy('name');
    }
}
