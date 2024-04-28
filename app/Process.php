<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Process
 */
class Process extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'processes';

    public static $searchable = [
        'name',
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
        'name',
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

    public function securityControls()
    {
        return $this->belongsToMany(SecurityControl::class, 'security_control_process')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
