<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Information
 */
class Information extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'information';

    public static array $searchable = [
        'name',
        'description',
        'owner',
        'constraints',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'owner',
        'administrator',
        'storage',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'sensitivity',
        'constraints',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<Database, $this> */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }
}
