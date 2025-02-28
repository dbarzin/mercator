<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'certificates';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'start_validity',
        'end_validity',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getStartValidityAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartValidityAttribute($value)
    {
        $this->attributes['start_validity'] =
            $this->parseDate(
                $value,
                config('panel.date_format'));
    }

    public function getEndValidityAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEndValidityAttribute($value)
    {
        $this->attributes['end_validity'] = 
            $this->parseDate(
                $value,
                config('panel.date_format'));
    }

    public function getLastNotificationAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function logical_servers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    private function parseDate($value, $format = null)
    {
        $format = $format ? $format : config('panel.date_format');

        try {
            return $value ? Carbon::createFromFormat($format, $value)->format('Y-m-d') : null;
        } catch (\Exception $e) {
            Log::error('Invalid date format: ' . $value . ' with format ' . $format);
            return null;
        }
    }
}
