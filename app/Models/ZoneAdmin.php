<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneAdmin extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'zone_admins';

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
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
