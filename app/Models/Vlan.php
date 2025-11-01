<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Vlan
 */
class Vlan extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public static array $searchable = [
        'name',
        'description',
    ];

    public $table = 'vlans';

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'vlan_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<PhysicalRouter, self> */
    public function physicalRouters(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalRouter::class)->orderBy('name');
    }

    /** @return HasMany<Subnetwork, self> */
    public function subnetworks(): HasMany
    {
        return $this->hasMany(Subnetwork::class, 'vlan_id', 'id')->orderBy('name');
    }
}
