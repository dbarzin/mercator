<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Gateway extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'gateways';

    public static $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'authentification',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function gatewaySubnetworks()
    {
        return $this->hasMany(Subnetword::class, 'gateway_id', 'id');
    }
}
