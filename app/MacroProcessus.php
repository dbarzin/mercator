<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\MacroProcessus
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $io_elements
 * @property int|null $security_need_c
 * @property string|null $owner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $security_need_i
 * @property int|null $security_need_a
 * @property int|null $security_need_t
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Process[] $processes
 * @property-read int|null $processes_count
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus newQuery()
 * @method static \Illuminate\Database\Query\Builder|MacroProcessus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus query()
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereIoElements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereSecurityNeedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereSecurityNeedC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereSecurityNeedI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereSecurityNeedT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MacroProcessus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|MacroProcessus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MacroProcessus withoutTrashed()
 * @mixin \Eloquent
 */
class MacroProcessus extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'macro_processuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'io_elements',
        'owner',
    ];

    protected $fillable = [
        'name',
        'description',
        'io_elements',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'owner',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'macroprocess_id', 'id')->orderBy("identifiant");
    }
}
