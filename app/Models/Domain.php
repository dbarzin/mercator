<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\DomainFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

/**
 * App\Domain
 */
class Domain extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

    public $table = 'domains';

    public static string $prefix = 'DOMAIN_';

    public static string $icon = '/images/domain.png';

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
        'domain_ctrl_cnt',
        'user_count',
        'machine_count',
        'relation_inter_domaine',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return DomainFactory::new();
    }

    /** @return BelongsToMany<ForestAd, $this> */
    public function forestAds(): BelongsToMany
    {
        return $this->belongsToMany(ForestAd::class)->orderBy('name');
    }

    /** @return HasMany<LogicalServer, $this> */
    public function logicalServers(): HasMany
    {
        return $this->hasMany(LogicalServer::class, 'domain_id');
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel1(Builder $query): Builder
    {
        return $query
            ->whereNotNull('description')
            ->whereNotNull('domain_ctrl_cnt')
            ->whereNotNull('user_count')
            ->whereNotNull('machine_count')
            ->whereNotNull('relation_inter_domaine');
    }
}
