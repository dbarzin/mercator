<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\AdminUser
 */
class AdminUser extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'admin_users';

    public static $searchable = [
        'user_id',
        'firstname',
        'lastname',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'attributes',
        'firstname',
        'lastname',
        'domain_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsTo<DomaineAd, $this> */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class, 'admin_user_m_application', 'admin_user_id', 'm_application_id');
    }
}
