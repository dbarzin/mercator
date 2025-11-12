<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\DomaineAd
 */
class DomaineAd extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'domaine_ads';

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
        'description',
        'domain_ctrl_cnt',
        'user_count',
        'machine_count',
        'relation_inter_domaine',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<ForestAd, $this> */
    public function forestAds(): BelongsToMany
    {
        return $this->belongsToMany(ForestAd::class)->orderBy('name');
    }

    /** @return HasMany<LogicalServer, $this> */
    public function logicalServers(): HasMany
    {
        return $this->hasMany(LogicalServer::class, 'domain_id');
    }
}
