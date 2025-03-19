<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function physicalServers()
    {
        return $this->belongsToMany(PhysicalServer::class)->orderBy('name');
    }

    public function serverIds()
    {
        return $this->belongsToMany(PhysicalServer::class)->pluck('id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class)->orderBy('document_id');
    }

    public function databases()
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function domain()
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class)->orderBy('name');
    }

    public function containers()
    {
        return $this->belongsToMany(Container::class)->orderBy('name');
    }

}
