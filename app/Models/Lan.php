<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\LanFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\Lan
 */
class Lan extends Model
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'lans';

    public static string $prefix = 'LAN_';

    public static string $icon = '/images/lan.png';

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

    protected static function newFactory(): Factory
    {
        return LanFactory::new();
    }

    /** @return BelongsToMany<Man, $this> */
    public function Mans(): BelongsToMany
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    /** @return BelongsToMany<Wan, $this> */
    public function Wans(): BelongsToMany
    {
        return $this->belongsToMany(Wan::class)->orderBy('name');
    }
}
