<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\AdminUserFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\AdminUser
 */
class AdminUser extends Model implements HasPrefix, HasIconContract
{
    use Auditable, HasFactory, HasUniqueIdentifier, SoftDeletes, HasIcon;

    public $table = 'admin_users';

    public static string $prefix = 'USER_';

    public static string $icon = '/images/actor.png';

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
        'icon_id',
        'firstname',
        'lastname',
        'domain_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return AdminUserFactory::new();
    }

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
