<?php


namespace App\Models;

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
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'logical_servers';

    public static array $searchable = [
        'name',
        'type',
        'description',
        'configuration',
        'net_services',
    ];

    protected array $dates = [
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
        'domain_id',
        'environment',
        'net_services',
        'configuration',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<MApplication, self> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<PhysicalServer, self> */
    public function physicalServers(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalServer::class)->orderBy('name');
    }

    /** @return \Illuminate\Support\Collection<int, int> */
    public function serverIds(): \Illuminate\Support\Collection
    {
        return $this->belongsToMany(PhysicalServer::class)->pluck('id');
    }

    /** @return BelongsToMany<Document, self> */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class)->orderBy('document_id');
    }

    /** @return BelongsToMany<Database, self> */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    /** @return BelongsToMany<Cluster, self> */
    public function clusters(): BelongsToMany
    {
        return $this->belongsToMany(Cluster::class);
    }

    /** @return BelongsTo<DomaineAd, self> */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    /** @return BelongsToMany<Certificate, self> */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)->orderBy('name');
    }

    /** @return BelongsToMany<Container, self> */
    public function containers(): BelongsToMany
    {
        return $this->belongsToMany(Container::class)->orderBy('name');
    }
}
