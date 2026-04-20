<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\NetworkSwitchFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\NetworkSwitch
 */
class NetworkSwitch extends Model
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'network_switches';

    public static string $prefix = 'LSWITCH_';

    public static string $icon = '/images/switch.png';

    public static array $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return NetworkSwitchFactory::new();
    }

    /**
     * Get the physical switches related to this NetworkSwitch, ordered by name.
     *
     * @return BelongsToMany<PhysicalSwitch, $this> The related PhysicalSwitch models ordered by name.
     */
    public function physicalSwitches(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalSwitch::class)->orderBy('name');
    }

    /**
     * Get VLANs associated with this network switch, ordered by name.
     *
     * @return BelongsToMany<Vlan, $this> The many-to-many relation instance for the related `Vlan` models.
     */
    public function vlans(): BelongsToMany
    {
        return $this->belongsToMany(Vlan::class)->orderBy('name');
    }
}
