<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\ZoneAdminFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneAdmin extends Model implements HasIconContract, HasPrefix
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'zone_admins';

    public static string $prefix = 'ZONE_';

    public static string $icon = '/images/zoneadmin.png';

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return ZoneAdminFactory::new();
    }

    /** @return HasMany<Annuaire, $this> */
    public function annuaires(): HasMany
    {
        return $this->hasMany(Annuaire::class, 'zone_admin_id', 'id')->orderBy('name');
    }

    /** @return HasMany<ForestAd, $this> */
    public function forestAds(): HasMany
    {
        return $this->hasMany(ForestAd::class, 'zone_admin_id', 'id')->orderBy('name');
    }
}
