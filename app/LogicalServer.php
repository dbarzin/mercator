<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class LogicalServer extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'logical_servers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'configuration',
        'net_services',
    ];

    protected $fillable = [
        'name',
        'description',
        'operating_system',
        'address_ip',
        'cpu',
        'memory',
        'environment',
        'net_services',
        'configuration',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class);
    }

    public function servers()
    {
        return $this->belongsToMany(PhysicalServer::class);
    }
}
