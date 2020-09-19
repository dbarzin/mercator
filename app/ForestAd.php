<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class ForestAd extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'forest_ads';

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
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function zone_admin()
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }

    public function domaines()
    {
        return $this->belongsToMany(DomaineAd::class);
    }
}
