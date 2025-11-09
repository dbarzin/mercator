<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\NetworkSwitch
 */
class NetworkSwitch extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'network_switches';

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

    /**
     * Get the physical switches related to this network switch, ordered by name.
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Vlan> A many-to-many relation for the related `Vlan` models, ordered by the `name` attribute.
     */
    public function vlans(): BelongsToMany
    {
        return $this->belongsToMany(Vlan::class)->orderBy('name');
    }

}