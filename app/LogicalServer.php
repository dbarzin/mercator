<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\LogicalServer
 */
class LogicalServer extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'logical_servers';

    public static $searchable = [
        'name',
        'type',
        'description',
        'configuration',
        'net_services',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'active',
        'description',
        'operating_system',
        'install_date',
        'attributes',
        'patching_frequency',
        'update_date',
        'next_update',
        'address_ip',
        'cpu',
        'memory',
        'disk',
        'disk_used',
        'cluster_id',
        'domain_id',
        'environment',
        'net_services',
        'configuration',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function physicalServers(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalServer::class)->orderBy('name');
    }

    public function serverIds(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalServer::class)->pluck('id');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class)->orderBy('document_id');
    }

    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)->orderBy('name');
    }

    public function containers(): BelongsToMany
    {
        return $this->belongsToMany(Container::class)->orderBy('name');
    }
}
