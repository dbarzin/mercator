<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Workstation
 *
 * @mixin \Eloquent
 */
class Workstation extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'workstations';

    public static $searchable = [
        'name',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'icon_id',
        'site_id',
        'building_id',
        'cpu',
        'memory',
        'disk',
        'operating_system',
        'address_ip',
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

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
