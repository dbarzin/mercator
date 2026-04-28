<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\ApplicationBlockFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationBlock
 */
class ApplicationBlock extends Model implements HasPrefix, HasIconContract
{
    use HasIcon, Auditable, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'application_blocks';

    public static string $prefix = 'BLOCK_';

    public static string $icon = '/images/applicationblock.png';

    public static array $searchable = [
        'name',
        'description',
        'responsible',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return ApplicationBlockFactory::new();
    }

    /** @return HasMany<MApplication, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(MApplication::class, 'application_block_id', 'id')->orderBy('name');
    }
}
