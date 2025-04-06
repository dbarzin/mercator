<?php

namespace App;

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
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'application_blocks';

    public static $searchable = [
        'name',
        'description',
        'responsible',
    ];

    protected $dates = [
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

    public function applications(): HasMany
    {
        return $this->hasMany(MApplication::class, 'application_block_id', 'id')->orderBy('name');
    }
}
