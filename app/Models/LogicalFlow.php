<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\LogicalFlowFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUniqueIdentifier;

/**
 * App\LogicalFlow
 */
class LogicalFlow extends Model
{
    use Auditable, HasUniqueIdentifier, HasFactory, SoftDeletes;

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
        'security_device_source_id',
        'subnetwork_source_id',
        'cluster_source_id',
        // Destinations
        'logical_server_dest_id',
        'peripheral_dest_id',
        'physical_server_dest_id',
        'storage_device_dest_id',
        'workstation_dest_id',
        'physical_security_device_dest_id',
        'security_device_dest_id',
        'subnetwork_dest_id',
        'cluster_dest_id',
        // Others
        'users',
        'schedule',
        'action',
    ];

    /**
     * Mapping des champs ID vers les noms de relations pour les sources
     */
    private const SOURCE_RELATIONS = [
        'logical_server_source_id' => 'logicalServerSource',
        'peripheral_source_id' => 'peripheralSource',
        'physical_server_source_id' => 'physicalServerSource',
        'storage_device_source_id' => 'storageDeviceSource',
        'workstation_source_id' => 'workstationSource',
        'physical_security_device_source_id' => 'physicalSecurityDeviceSource',
        'security_device_source_id' => 'securityDeviceSource',
        'subnetwork_source_id' => 'subnetworkSource',
        'cluster_source_id' => 'clusterSource',
    ];

    /**
     * Mapping des champs ID vers les noms de relations pour les destinations
     */
    private const DEST_RELATIONS = [
        'logical_server_dest_id' => 'logicalServerDest',
        'peripheral_dest_id' => 'peripheralDest',
        'physical_server_dest_id' => 'physicalServerDest',
        'storage_device_dest_id' => 'storageDeviceDest',
        'workstation_dest_id' => 'workstationDest',
        'physical_security_device_dest_id' => 'physicalSecurityDeviceDest',
        'security_device_dest_id' => 'securityDeviceDest',
        'subnetwork_dest_id' => 'subnetworkDest',
        'cluster_dest_id' => 'clusterDest',
    ];

    protected static function newFactory(): Factory
    {
        return LogicalFlowFactory::new();
    }

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

    /** @return BelongsTo<SecurityDevice, $this> */
    public function securityDeviceSource(): BelongsTo
    {
        return $this->belongsTo(SecurityDevice::class, 'security_device_source_id');
    }

    /** @return BelongsTo<Cluster, $this> */
    public function clusterSource(): BelongsTo
    {
        return $this->belongsTo(Cluster::class, 'cluster_source_id');
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

    /** @return BelongsTo<SecurityDevice, $this> */
    public function securityDeviceDest(): BelongsTo
    {
        return $this->belongsTo(SecurityDevice::class, 'security_device_dest_id');
    }

    /** @return BelongsTo<Cluster, $this> */
    public function clusterDest(): BelongsTo
    {
        return $this->belongsTo(Cluster::class, 'cluster_dest_id');
    }

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ Router ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    /** @return BelongsTo<Router, $this> */
    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class, 'router_id');
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

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ UIDs ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    /**
     * Retourne l'UID de la source (ex: "LSERVER_42")
     * Utilise le préfixe statique défini dans chaque modèle
     */
    public function sourceId(): ?string
    {
        return $this->getEntityUID(self::SOURCE_RELATIONS);
    }

    /**
     * Retourne l'UID de la destination (ex: "WORK_15")
     * Utilise le préfixe statique défini dans chaque modèle
     */
    public function destinationId(): ?string
    {
        return $this->getEntityUID(self::DEST_RELATIONS);
    }

    /**
     * Récupère l'UID d'une entité sans charger la relation complète
     * Utilise la propriété statique $prefix de chaque modèle
     *
     * @param array<string, string> $relations Mapping field => relationName
     * @return string|null L'UID construit (PREFIX_ID) ou null si aucune relation n'est définie
     */
    private function getEntityUID(array $relations): ?string
    {
        foreach ($relations as $field => $relationName) {
            if ($this->$field !== null) {
                // Récupère la classe du modèle via la relation
                $relation = $this->$relationName();
                $modelClass = get_class($relation->getRelated());

                // Utilise le préfixe statique de la classe cible
                // Ex: LogicalServer::$prefix = "LSERVER_"
                if (property_exists($modelClass, 'prefix')) {
                    return $modelClass::$prefix . $this->$field;
                }

                // Fallback si le modèle n'a pas de préfixe (ne devrait pas arriver)
                throw new \LogicException(
                    sprintf('Model %s must have a static $prefix property', $modelClass)
                );
            }
        }

        return null;
    }

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ Private ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    /**
     * Vérifie si une IP est contenue dans un CIDR
     * Supporte IPv4 et IPv6
     */
    private function contains(string $cidr, string $ip): bool
    {
        if ((str_contains($ip, '.') && str_contains($cidr, '.')) ||
            (str_contains($ip, ':') && str_contains($cidr, ':'))) {
            // Get mask bits
            $parts = explode('/', $cidr);
            if (count($parts) !== 2) {
                \Log::warning("LogicalFlow: invalid CIDR format", ['cidr' => $cidr, 'id' => $this->id]);
                return false;
            }
            [$net, $maskBits] = $parts;

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