<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\LogicalFlow
 */
class LogicalFlow extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'logical_flows';

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'chain',
        'interface',
        'router_id',
        'priority',
        'protocol',
        // IP / Range
        'source_ip_range',
        'dest_ip_range',
        'source_port',
        'dest_port',
        // Sources
        'logical_server_source_id',
        'peripheral_source_id',
        'physical_server_source_id',
        'storage_device_source_id',
        'workstation_source_id',
        'physical_security_device_source_id',
        // Destinations
        'logical_server_dest_id',
        'peripheral_dest_id',
        'physical_server_dest_id',
        'storage_device_dest_id',
        'workstation_dest_id',
        'physical_security_device_dest_id',
        // Others
        'users',
        'schedule',
        'action',
    ];

    /* ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅  Sources  ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ */

    /** @return BelongsTo<LogicalServer, $this> */
    public function logicalServerSource(): BelongsTo
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_source_id');
    }

    /** @return BelongsTo<Peripheral, $this> */
    public function peripheralSource(): BelongsTo
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_source_id');
    }

    /** @return BelongsTo<PhysicalServer, $this> */
    public function physicalServerSource(): BelongsTo
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_source_id');
    }

    /** @return BelongsTo<StorageDevice, $this> */
    public function storageDeviceSource(): BelongsTo
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_source_id');
    }

    /** @return BelongsTo<Workstation, $this> */
    public function workstationSource(): BelongsTo
    {
        return $this->belongsTo(Workstation::class, 'workstation_source_id');
    }

    /** @return BelongsTo<PhysicalSecurityDevice, $this> */
    public function physicalSecurityDeviceSource(): BelongsTo
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_source_id');
    }

    /** @return BelongsTo<Subnetwork, $this> */
    public function subnetworkSource(): BelongsTo
    {
        return $this->belongsTo(Subnetwork::class, 'subnetwork_source_id');
    }

    /* ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ Destinations ⋅.˳˳.⋅ॱ˙˙ॱ⋅.˳˳.⋅ॱ˙˙ॱᐧ.˳˳.⋅ */

    /** @return BelongsTo<LogicalServer, $this> */
    public function logicalServerDest(): BelongsTo
    {
        return $this->belongsTo(LogicalServer::class, 'logical_server_dest_id');
    }

    /** @return BelongsTo<Peripheral, $this> */
    public function peripheralDest(): BelongsTo
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_dest_id');
    }

    /** @return BelongsTo<PhysicalServer, $this> */
    public function physicalServerDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalServer::class, 'physical_server_dest_id');
    }

    /** @return BelongsTo<StorageDevice, $this> */
    public function storageDeviceDest(): BelongsTo
    {
        return $this->belongsTo(StorageDevice::class, 'storage_device_dest_id');
    }

    /** @return BelongsTo<Workstation, $this> */
    public function workstationDest(): BelongsTo
    {
        return $this->belongsTo(Workstation::class, 'workstation_dest_id');
    }

    /** @return BelongsTo<PhysicalSecurityDevice, $this> */
    public function physicalSecurityDeviceDest(): BelongsTo
    {
        return $this->belongsTo(PhysicalSecurityDevice::class, 'physical_security_device_dest_id');
    }

    /** @return BelongsTo<Subnetwork, $this> */
    public function subnetworkDest(): BelongsTo
    {
        return $this->belongsTo(Subnetwork::class, 'subnetwork_dest_id');
    }

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ IP ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    public function isSource(?string $ip): bool
    {
        return ($this->source_ip_range !== null) &&
            ($ip !== null) &&
            $this->contains($this->source_ip_range, $ip);
    }

    public function isDestination(?string $ip): bool
    {
        return ($this->dest_ip_range !== null) &&
            ($ip !== null) &&
            $this->contains($this->dest_ip_range, $ip);
    }

    /** @return BelongsTo<Router, $this> */
    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class, 'router_id');
    }

    public function sourceId(): ?string
    {
        if ($this->logical_server_source_id !== null) {
            return 'LSERVER_'.$this->logical_server_source_id;
        }
        if ($this->peripheral_source_id !== null) {
            return 'PERIF_'.$this->peripheral_source_id;
        }
        if ($this->physical_server_source_id !== null) {
            return 'PSERVER_'.$this->physical_server_source_id;
        }
        if ($this->storage_device_source_id !== null) {
            return 'STORAGE_'.$this->storage_device_source_id;
        }
        if ($this->workstation_source_id !== null) {
            return 'WORK_'.$this->workstation_source_id;
        }
        if ($this->physical_security_device_source_id !== null) {
            return 'PSECURITY_'.$this->physical_security_device_source_id;
        }
        if ($this->subnetwork_source_id !== null) {
            return 'SUBNETWORK_'.$this->subnetwork_source_id;
        }

        return null;
    }

    public function destinationId(): ?string
    {
        if ($this->logical_server_dest_id !== null) {
            return 'LSERVER_'.$this->logical_server_dest_id;
        }
        if ($this->peripheral_dest_id !== null) {
            return 'PERIF_'.$this->peripheral_dest_id;
        }
        if ($this->physical_server_dest_id !== null) {
            return 'PSERVER_'.$this->physical_server_dest_id;
        }
        if ($this->storage_device_dest_id !== null) {
            return 'STORAGE_'.$this->storage_device_dest_id;
        }
        if ($this->workstation_dest_id !== null) {
            return 'WORK_'.$this->workstation_dest_id;
        }
        if ($this->physical_security_device_dest_id !== null) {
            return 'PSECURITY_'.$this->physical_security_device_dest_id;
        }
        if ($this->subnetwork_dest_id !== null) {
            return 'SUBNETWORK_'.$this->subnetwork_dest_id;
        }

        return null;
    }

    /**
     * Does the given IP match the CIDR prefix
     */
    private function contains(string $cidr, string $ip): bool
    {
        if ((str_contains($ip, '.') && str_contains($cidr, '.')) ||
              (str_contains($ip, ':') && str_contains($cidr, ':'))) {
            // Get mask bits
            [$net, $maskBits] = explode('/', $cidr);

            // Size
            $size = ! str_contains($ip, ':') ? 4 : 16;

            // Convert to binary
            $ip = inet_pton(trim($ip));
            $net = inet_pton(trim($net));
            if (! $ip || ! $net) {
                return false;
            }

            // Build mask
            $solid = intdiv(intval($maskBits), 8);
            $solidBits = $solid * 8;
            $mask = str_repeat(chr(255), $solid);
            for ($i = $solidBits; $i < $maskBits; $i += 8) {
                $bits = max(0, min(8, intval($maskBits) - $i));
                $mask .= chr(pow(2, $bits) - 1 << 8 - $bits);
            }
            $mask = str_pad($mask, $size, chr(0));

            // Compare the mask
            return ($ip & $mask) === ($net & $mask);
        }

        return false;
    }
}
