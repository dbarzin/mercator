<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalLink extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'physical_links';

    public static $searchable = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'src_port',
        'dest_port',
        'peripheral_src_id', 'phone_src_id', 'physical_router_src_id', 'physical_security_device_src_id', 'physical_server_src_id', 'physical_switch_src_id', 'storage_device_src_id', 'wifi_terminal_src_id', 'workstation_src_id', 'logical_server_src_id', 'network_switch_src_id', 'router_src_id',
        'peripheral_dest_id', 'phone_dest_id', 'physical_router_dest_id', 'physical_security_device_dest_id', 'physical_server_dest_id', 'physical_switch_dest_id', 'storage_device_dest_id', 'wifi_terminal_dest_id', 'workstation_dest_id', 'logical_server_dest_id', 'network_switch_dest_id', 'router_dest_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /* ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅  Sources  ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ */

    public function peripheralSrc(): BelongsTo
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_src_id');
    }

    public function phoneSrc(): BelongsTo
    {
        return $this->belongsTo(Phone::class, 'phone_src_id');
    }

    public function physicalRouterSrc(): BelongsTo
    {
        return $this->belongsTo(PhysicalRouter::class, 'physical_router_src_id');
    }

    public function physicalSecurityDeviceSrc(): BelongsTo
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_src_id');
    }

    public function physicalServerSrc(): BelongsTo
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_src_id');
    }

    public function physicalSwitchSrc(): BelongsTo
    {
        return $this->belongsTo(PhysicalSwitch::class, 'physical_switch_src_id');
    }

    public function storageDeviceSrc(): BelongsTo
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_src_id');
    }

    public function wifiTerminalSrc(): BelongsTo
    {
        return $this->belongsTo(WifiTerminal::class, 'wifi_terminal_src_id');
    }

    public function workstationSrc(): BelongsTo
    {
        return $this->belongsTo(Workstation::class, 'workstation_src_id');
    }

    public function routerSrc(): BelongsTo
    {
        return $this->belongsTo(Router::class, 'router_src_id');
    }

    public function networkSwitchSrc(): BelongsTo
    {
        return $this->belongsTo(NetworkSwitch::class, 'network_switch_src_id');
    }

    public function logicalServerSrc(): BelongsTo
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_src_id');
    }

    /* ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ Destinations ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ */

    public function peripheralDest(): BelongsTo
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_dest_id');
    }

    public function phoneDest(): BelongsTo
    {
        return $this->belongsTo(Phone::class, 'phone_dest_id');
    }

    public function physicalRouterDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalRouter::class, 'physical_router_dest_id');
    }

    public function physicalSecurityDeviceDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_dest_id');
    }

    public function physicalServerDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_dest_id');
    }

    public function physicalSwitchDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalSwitch::class, 'physical_switch_dest_id');
    }

    public function storageDeviceDest(): BelongsTo
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_dest_id');
    }

    public function wifiTerminalDest(): BelongsTo
    {
        return $this->belongsTo(WifiTerminal::class, 'wifi_terminal_dest_id');
    }

    public function workstationDest(): BelongsTo
    {
        return $this->belongsTo(Workstation::class, 'workstation_dest_id');
    }

    public function routerDest(): BelongsTo
    {
        return $this->belongsTo(Router::class, 'router_dest_id');
    }

    public function networkSwitchDest(): BelongsTo
    {
        return $this->belongsTo(NetworkSwitch::class, 'network_switch_dest_id');
    }

    public function logicalServerDest(): BelongsTo
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_dest_id');
    }

    // -------------------------------------------------------------------

    public function sourceId() : ?string {
        if ($this->peripheral_src_id !== null) {
            return 'PERIF_' . $this->peripheral_src_id;
        } elseif ($this->phone_src_id !== null) {
            return 'PHONE_' . $this->phone_src_id;
        } elseif ($this->physical_router_src_id !== null) {
            return 'PROUTER_' . $this->physical_router_src_id;
        } elseif ($this->physical_security_device_src_id !== null) {
            return 'SECURITY_' . $this->physical_security_device_src_id;
        } elseif ($this->physical_server_src_id !== null) {
            return 'PSERVER_' . $this->physical_server_src_id;
        } elseif ($this->physical_switch_src_id !== null) {
            return 'SWITCH_' . $this->physical_switch_src_id;
        } elseif ($this->storage_device_src_id !== null) {
            return 'STORAGE_' . $this->storage_device_src_id;
        } elseif ($this->wifi_terminal_src_id !== null) {
            return 'WIFI_' . $this->wifi_terminal_src_id;
        } elseif ($this->workstation_src_id !== null) {
            return 'WORK_' . $this->workstation_src_id;
        } elseif ($this->logical_server_src_id !== null) {
            return 'LSERVER_' . $this->logical_server_src_id;
        } elseif ($this->network_switch_src_id !== null) {
            return 'LSWITCH_' . $this->network_switch_src_id;
        } elseif ($this->router_src_id !== null) {
            return 'ROUTER_' . $this->router_src_id;
        } else
            return null;
    }

    public function destinationId(): ?string {
        // dd($this, $this->workstation_dest_id, $this->workstation_dest_id!==null);
        if ($this->peripheral_dest_id !== null) {
            return 'PERIF_' . $this->peripheral_dest_id;
        } elseif ($this->phone_dest_id !== null) {
            return 'PHONE_' . $this->phone_dest_id;
        } elseif ($this->physical_router_dest_id !== null) {
            return 'PROUTER_' . $this->physical_router_dest_id;
        } elseif ($this->physical_security_device_dest_id !== null) {
            return 'SECURITY_' . $this->physical_security_device_dest_id;
        } elseif ($this->physical_server_dest_id !== null) {
            return 'PSERVER_' . $this->physical_server_dest_id;
        } elseif ($this->physical_switch_dest_id !== null) {
            return 'SWITCH_' . $this->physical_switch_dest_id;
        } elseif ($this->storage_device_dest_id !== null) {
            return 'STORAGE_' . $this->storage_device_dest_id;
        } elseif ($this->wifi_terminal_dest_id !== null) {
            return 'WIFI_' . $this->wifi_terminal_dest_id;
        } elseif ($this->workstation_dest_id !== null) {
            return 'WORK_' . $this->workstation_dest_id;
        } elseif ($this->logical_server_dest_id !== null) {
            return 'LSERVER_' . $this->logical_server_dest_id;
        } elseif ($this->network_switch_dest_id !== null) {
            return 'LSWITCH_' . $this->network_switch_dest_id;
        } elseif ($this->router_dest_id !== null) {
            return 'ROUTER_' . $this->router_dest_id;
        } else
            return null;
    }

}
