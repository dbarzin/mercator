<?php

namespace App;

use App\NetworkSwitches;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

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
