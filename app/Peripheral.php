<?php

namespace App;

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
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'peripherals';

    public static $searchable = [
        'name',
        'type',
        'description',
        'responsible',
    ];

    protected $dates = [
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

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'provider_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
