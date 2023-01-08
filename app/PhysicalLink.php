<?php

namespace App;

use App\Peripheral;
use App\Phone;
use App\PhysicalRouter;
use App\PhysicalSecurityDevice;
use App\PhysicalServer;
use App\PhysicalSwitch;
use App\StorageDevice;
use App\WifiTerminal;
use App\Workstation;

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
        return $this->belongsTo(Phone::class, 'physical_router_src_id');
    }

    public function physicalSecurityDeviceSrc()
    {
        return $this->belongsTo(Phone::class, 'physical_security_device_src_id');
    }

    public function physicalServerSrc()
    {
        return $this->belongsTo(Phone::class, 'physical_server_src_id');
    }

    public function physicalSwitchSrc()
    {
        return $this->belongsTo(Phone::class, 'physical_switch_src_id');
    }

    public function physicalStorageSrc()
    {
        return $this->belongsTo(Phone::class, 'physical_storage_src_id');
    }

    public function physicalWifiTerminalSrc()
    {
        return $this->belongsTo(Phone::class, 'wifi_terminal_src_id');
    }

    public function physicalWorkstationSrc()
    {
        return $this->belongsTo(Phone::class, 'workstation_src_id');
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
        return $this->belongsTo(Phone::class, 'physical_router_dest_id');
    }

    public function physicalSecurityDeviceDest()
    {
        return $this->belongsTo(Phone::class, 'physical_security_device_dest_id');
    }

    public function physicalServerDest()
    {
        return $this->belongsTo(Phone::class, 'physical_server_dest_id');
    }

    public function physicalSwitchDest()
    {
        return $this->belongsTo(Phone::class, 'physical_switch_dest_id');
    }

    public function physicalStorageDest()
    {
        return $this->belongsTo(Phone::class, 'physical_storage_dest_id');
    }

    public function physicalWifiTerminalDest()
    {
        return $this->belongsTo(Phone::class, 'wifi_terminal_dest_id');
    }

    public function physicalWorkstationDest()
    {
        return $this->belongsTo(Phone::class, 'workstation_dest_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
