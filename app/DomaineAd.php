<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\DomaineAd
 */
class DomaineAd extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'domaine_ads';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'domain_ctrl_cnt',
        'user_count',
        'machine_count',
        'relation_inter_domaine',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function forestAds(): BelongsToMany
    {
        return $this->belongsToMany(ForestAd::class)->orderBy('name');
    }

    public function logicalServers(): HasMany
    {
        return $this->hasMany(LogicalServer::class, 'domain_id');
    }
}
