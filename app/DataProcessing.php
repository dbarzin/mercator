<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Actor
 */
class DataProcessing extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'data_processing';

    public static $searchable = [
        'name',
        'description',
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
        'purpose',
        'categories',
        'recipients',
        'transfert',
        'retention',
        'controls',
    ];

    public function processes()
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function informations()
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
