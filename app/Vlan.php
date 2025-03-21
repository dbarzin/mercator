<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Vlan
 */
class Vlan extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'vlans';

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
        'vlan_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function vlanPhysicalRouters()
    {
        return $this->belongsToMany(PhysicalRouter::class)->orderBy('name');
    }

    public function subnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'vlan_id', 'id')->orderBy('name');
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
