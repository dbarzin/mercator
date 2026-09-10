<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\LanFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

/**
 * App\Lan
 */
class Lan extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

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
        'ext_refs',
        'name',
        'type',
        'attributes',
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

    /** @param Builder<static> $query */
    public function scopeMaturityLevel1(Builder $query): Builder
    {
        return $query->whereNotNull('description');
    }
}
