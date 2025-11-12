<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\MacroProcessus
 */
class MacroProcessus extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'macro_processuses';

    public static array $searchable = [
        'name',
        'description',
        'io_elements',
        'owner',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'io_elements',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'owner',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return HasMany<Process, $this> */
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class, 'macroprocess_id', 'id')->orderBy('name');
    }
}
