<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoneAdmin extends Model
{
    use HasFactory, SoftDeletes, Auditable;

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

    public function zoneAdminAnnuaires(): HasMany
    {
        return $this->hasMany(Annuaire::class, 'zone_admin_id', 'id')->orderBy('name');
    }

    public function zoneAdminForestAds(): HasMany
    {
        return $this->hasMany(ForestAd::class, 'zone_admin_id', 'id')->orderBy('name');
    }
}
