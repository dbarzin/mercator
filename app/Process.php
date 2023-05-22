<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Entity> $entities
 * @property-read int|null $entities_count
 * @property-read \App\MacroProcessus|null $macroProcess
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Information> $processInformation
 * @property-read int|null $process_information_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $applications
 * @property-read int|null $processes_m_applications_count
 *
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
 *
 * @mixin \Eloquent
 */
class Process extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'processes';

    public static $searchable = [
        'identifiant',
        'description',
        'in_out',
        'owner',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function processInformation()
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'process_id', 'id')->orderBy('name');
    }

    public function dataProcesses()
    {
        return $this->belongsToMany(DataProcessing::class, 'data_processing_process')->orderBy('name');
    }

    public function macroProcess()
    {
        return $this->belongsTo(MacroProcessus::class, 'macroprocess_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
