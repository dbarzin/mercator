<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

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
        return $this->belongsToMany(Information::class);
    }

    public function processesMApplications()
    {
        return $this->belongsToMany(MApplication::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class);
    }

    public function macroProcess()
    {
        return $this->belongsTo(MacroProcessus::class, 'macroprocess_id');
    }

}
