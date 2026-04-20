<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Factories\ActivityImpactFactory;
use App\Factories\PermissionFactory;

/**
 * App\Permission
 */
class Permission extends Model
{
    use HasFactory;

    public $table = 'permissions';

    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'module',
        'created_at',
        'updated_at',
    ];

    protected static function newFactory(): Factory
    {
        return PermissionFactory::new();
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('permissions_roles_map');
        });

        static::deleted(function () {
            Cache::forget('permissions_roles_map');
        });
    }
}
