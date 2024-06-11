<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\AdminUser
 */
class AdminUser extends Model
{
    use SoftDeletes, Auditable;

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

    public function domain()
    {
        return $this->belongsTo(DomaineAd::class, 'domain_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
