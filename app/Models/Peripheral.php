<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Peripheral
 */
class Peripheral extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'peripherals';

    public static array $searchable = [
        'name',
        'type',
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
        'domain',
        'type',
        'description',
        'icon_id',
        'provider_id',
        'responsible',
        'site_id',
        'building_id',
        'bay_id',
        'vendor',
        'product',
        'version',
        'address_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<MApplication, self> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsTo<Entity, self> */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'provider_id');
    }

    /** @return BelongsTo<Site, self> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @return BelongsTo<Building, self> */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /** @return BelongsTo<Bay, self> */
    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
