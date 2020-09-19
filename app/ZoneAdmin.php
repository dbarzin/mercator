<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class ZoneAdmin extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'zone_admins';

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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function zoneAdminAnnuaires()
    {
        return $this->hasMany(Annuaire::class, 'zone_admin_id', 'id');
    }

    public function zoneAdminForestAds()
    {
        return $this->hasMany(ForestAd::class, 'zone_admin_id', 'id');
    }
}
