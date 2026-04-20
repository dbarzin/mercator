<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\WanFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\Wan
 */
class Wan extends Model
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'wans';

    public static string $prefix = 'WAN_';

    public static string $icon = '/images/wan.png';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $searchable = [
        'name',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return WanFactory::new();
    }

    /** @return BelongsToMany<Man, $this> */
    public function mans(): BelongsToMany
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    /** @return BelongsToMany<Lan, $this> */
    public function lans(): BelongsToMany
    {
        return $this->belongsToMany(Lan::class)->orderBy('name');
    }
}
