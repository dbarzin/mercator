<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class PhysicalServer extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'physical_servers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'descrition',
        'configuration',
        'responsible',
    ];

    protected $fillable = [
        'name',
        'descrition',
        'configuration',
        'site_id',
        'building_id',
        'bay_id',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function serversLogicalServers()
    {
        return $this->belongsToMany(LogicalServer::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }

}
