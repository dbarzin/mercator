<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\PhysicalRouter
 */
class PhysicalRouter extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'physical_routers';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'site_id',
        'building_id',
        'bay_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

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

    public function routers(): BelongsToMany
    {
        return $this->belongsToMany(Router::class)->orderBy('name');
    }

    public function vlans(): BelongsToMany
    {
        return $this->belongsToMany(Vlan::class)->orderBy('name');
    }
}
