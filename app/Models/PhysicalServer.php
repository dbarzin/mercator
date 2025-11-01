<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\PhysicalServer
 */
class PhysicalServer extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'physical_servers';

    public static array $searchable = [
        'name',
        'type',
        'description',
        'configuration',
        'responsible',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'configuration',
        'address_ip',
        'cpu',
        'memory',
        'disk',
        'disk_used',
        'operating_system',
        'install_date',
        'update_date',
        'address_ip',
        'site_id',
        'building_id',
        'bay_id',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<MApplication, self> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Cluster, self> */
    public function clusters(): BelongsToMany
    {
        return $this->BelongsToMany(Cluster::class)->orderBy('name');
    }

    /** @return BelongsToMany<LogicalServer, self> */
    public function logicalServers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

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

    /** @return BelongsTo<Bay, self> */
    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
