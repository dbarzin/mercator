<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\ActorFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Traits\HasCartographers;

/**
 * App\Actor
 *
 * @property string|null $type
 * @property string|null $attributes
 */
class Actor extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use HasIcon, Auditable, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

    public $table = 'actors';

    public static string $prefix = 'ACTOR_';

    public static string $icon = '/images/actor.png';

    protected $fillable = [
        'ext_refs',
        'name',
        'contact',
        'nature',
        'type',
        'attributes',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $searchable = [
        'name',
        'nature',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return ActorFactory::new();
    }

    /** @return BelongsToMany<Operation, $this>
     */
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }

    public function graphs(): Collection
    {
        return once(fn() => Graph::query()
            ->select('id','name')
            ->where('class', '=', '2')
            ->whereLike('content', '%"#'.$this->getUID().'"%')
            ->get()
        );
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel2(Builder $query): Builder
    {
        return $query
            ->whereNotNull('contact')
            ->whereNotNull('nature')
            ->whereNotNull('type');
    }

}
