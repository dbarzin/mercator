<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Factories\ContainerFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Container
 */
class Container extends Model implements HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'containers';

    public static string $prefix = 'CONT_';

    public static string $icon = '/images/container.png';

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
        'type',
        'icon_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return ContainerFactory::new();
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Database, $this> */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logicalServers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }
}
