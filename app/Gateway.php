<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Gateway
 */
class Gateway extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'gateways';

    public static $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected $dates = [
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

    public function subnetworks(): HasMany
    {
        return $this->hasMany(Subnetwork::class, 'gateway_id', 'id')->orderBy('name');
    }
}
