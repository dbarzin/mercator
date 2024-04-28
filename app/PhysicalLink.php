<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalLink extends Model
{
    use SoftDeletes, Auditable;

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

    // Sources

    public function peripheralSrc()
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_src_id');
    }

    public function phoneSrc()
    {
        return $this->belongsTo(Phone::class, 'phone_src_id');
    }

    public function physicalRouterSrc()
    {
        return $this->belongsTo(PhysicalRouter::class, 'physical_router_src_id');
    }

    public function physicalSecurityDeviceSrc()
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_src_id');
    }

    public function physicalServerSrc()
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_src_id');
    }

    public function physicalSwitchSrc()
    {
        return $this->belongsTo(PhysicalSwitch::class, 'physical_switch_src_id');
    }

    public function storageDeviceSrc()
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_src_id');
    }

    public function wifiTerminalSrc()
    {
        return $this->belongsTo(WifiTerminal::class, 'wifi_terminal_src_id');
    }

    public function workstationSrc()
    {
        return $this->belongsTo(Workstation::class, 'workstation_src_id');
    }

    public function routerSrc()
    {
        return $this->belongsTo(Router::class, 'router_src_id');
    }

    public function networkSwitchSrc()
    {
        return $this->belongsTo(NetworkSwitch::class, 'network_switch_src_id');
    }

    public function logicalServerSrc()
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_src_id');
    }

    // Destinations

    public function peripheralDest()
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_dest_id');
    }

    public function phoneDest()
    {
        return $this->belongsTo(Phone::class, 'phone_dest_id');
    }

    public function physicalRouterDest()
    {
        return $this->belongsTo(PhysicalRouter::class, 'physical_router_dest_id');
    }

    public function physicalSecurityDeviceDest()
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_dest_id');
    }

    public function physicalServerDest()
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_dest_id');
    }

    public function physicalSwitchDest()
    {
        return $this->belongsTo(PhysicalSwitch::class, 'physical_switch_dest_id');
    }

    public function storageDeviceDest()
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_dest_id');
    }

    public function wifiTerminalDest()
    {
        return $this->belongsTo(WifiTerminal::class, 'wifi_terminal_dest_id');
    }

    public function workstationDest()
    {
        return $this->belongsTo(Workstation::class, 'workstation_dest_id');
    }

    public function routerDest()
    {
        return $this->belongsTo(Router::class, 'router_dest_id');
    }

    public function networkSwitchDest()
    {
        return $this->belongsTo(NetworkSwitch::class, 'network_switch_dest_id');
    }

    public function logicalServerDest()
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_dest_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
