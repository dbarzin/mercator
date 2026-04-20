<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\MacroProcessusFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\MacroProcessus
 */
class MacroProcessus extends Model
{
    use Auditable, HasIcon, HasFactory, HasUniqueIdentifier, SoftDeletes;

    public $table = 'macro_processuses';

    public static string $prefix = 'MACROPROCESS_';

    public static string $icon = '/images/macroprocess.png';

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

    protected static function newFactory(): Factory
    {
        return MacroProcessusFactory::new();
    }

    /** @return HasMany<Process, $this> */
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class, 'macroprocess_id', 'id')->orderBy('name');
    }
}
