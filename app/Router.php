<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Router
 */
class Router extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'routers';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'rules',
        'ip_addresses',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /*
    public function networkSwitches()
    {
        // TODO: to change
        return $this->hasMany(NetworkSwitches::class, 'router_id', 'id');
    }
    */
}
