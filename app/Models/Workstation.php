<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Workstation
 *
 * @mixin \Eloquent
 */
class Workstation extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'workstations';

    public static array $searchable = [
        'name',
        'type',
        'description',
        'serial_number',
        'manufacturer',
        'model',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'status',
        'description',
        'icon_id',

        // Model
        'manufacturer',
        'model',
        'serial_number',

        // Configuration
        'cpu',
        'memory',
        'disk',
        'operating_system',

        // Ownership
        'entity_id',
        'domain_id',
        'user_id',
        'other_user',

        // Warranty
        /*
        'purchase_date',
        'fin_value',
        'warranty',
        'warranty_start_date',
        'warranty_end_date',
        'warranty_period',
        */

        // Network
        'network_id',
        'network_port_type',
        'address_ip',
        'mac_address',

        // Location
        'site_id',
        'building_id',

        // Inventory
        'last_inventory_date',
        'update_source',
        'agent_version',

        // Auditable
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsTo<Site, self> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @return BelongsTo<Building, self> */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /** @return BelongsTo<Entity, self> */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    /** @return BelongsTo<DomaineAd, self> */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    /** @return BelongsTo<Network, self> */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    /** @return BelongsTo<AdminUser, self> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }

    /** @return BelongsToMany<MApplication, self> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
