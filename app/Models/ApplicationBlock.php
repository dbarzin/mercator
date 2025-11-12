<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationBlock
 */
class ApplicationBlock extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public static array $searchable = [
        'name',
        'description',
        'responsible',
    ];

    public $table = 'application_blocks';

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return HasMany<MApplication, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(MApplication::class, 'application_block_id', 'id')->orderBy('name');
    }
}
