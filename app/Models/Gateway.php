<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Gateway
 */
class Gateway extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'gateways';

    public static array $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'authentification',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return HasMany<Subnetwork, $this> */
    public function subnetworks(): HasMany
    {
        return $this->hasMany(Subnetwork::class, 'gateway_id', 'id')->orderBy('name');
    }
}
