<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Process
 *
 * @property int $id
 * @property string $identifiant
 * @property string|null $description
 * @property string|null $owner
 * @property int|null $security_need_c
 * @property string|null $in_out
 * @property int|null $dummy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $macroprocess_id
 * @property int|null $security_need_i
 * @property int|null $security_need_a
 * @property int|null $security_need_t
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity[] $entities
 * @property-read int|null $entities_count
 * @property-read \App\MacroProcessus|null $macroProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Information[] $processInformation
 * @property-read int|null $process_information_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MApplication[] $processesMApplications
 * @property-read int|null $processes_m_applications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Process newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Process newQuery()
 * @method static \Illuminate\Database\Query\Builder|Process onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Process query()
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereDummy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereIdentifiant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereInOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereMacroprocessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereSecurityNeedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereSecurityNeedC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereSecurityNeedI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereSecurityNeedT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Process whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Process withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Process withoutTrashed()
 * @mixin \Eloquent
 */
class Process extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'processes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'identifiant',
        'description',
        'in_out',
        'owner',
    ];

    protected $fillable = [
        'identifiant',
        'description',
        'in_out',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'owner',
        'macroprocess_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function processInformation()
    {
        return $this->belongsToMany(Information::class)->orderBy("name");
    }

    public function processesMApplications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy("name");
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->orderBy("name");
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class)->orderBy("name");
    }

    public function macroProcess()
    {
        return $this->belongsTo(MacroProcessus::class, 'macroprocess_id')->orderBy("name");
    }

}
