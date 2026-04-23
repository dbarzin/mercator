<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\AnnuaireFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\Annuaire *
 */
class Annuaire extends Model
{
    use HasIcon, Auditable, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'annuaires';

    public static string $prefix = 'ANNUAIRE_';

    public static string $icon = '/images/annuaire.png';

    public static array $searchable = [
        'name',
        'description',
        'solution',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'solution',
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected static function newFactory(): Factory
    {
        return AnnuaireFactory::new();
    }

    /** @return BelongsTo<ZoneAdmin, $this> */
    public function zone_admin(): BelongsTo
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }
}
