<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\ApplicationBlockFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

/**
 * App\ApplicationBlock
 */
class ApplicationBlock extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use HasIcon, Auditable, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

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
        'ext_refs',
        'name',
        'type',
        'attributes',
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

    /** @return HasMany<Application, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'application_block_id', 'id')->orderBy('name');
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel2(Builder $query): Builder
    {
        return $query
            ->whereNotNull('description')
            ->whereNotNull('responsible')
            ->whereExists(fn ($q) => $q
                ->from('applications')
                ->whereColumn('applications.application_block_id', 'application_blocks.id'));
    }
}
