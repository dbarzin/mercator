<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\AdminUser
 */
class AdminUser extends Model
{
    use HasFactory, SoftDeletes, Auditable;

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
        'firstname',
        'lastname',
        'type',
        'description',
        'domain_id',
        'local',
        'privileged',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }
}
