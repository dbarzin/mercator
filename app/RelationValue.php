<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RelationValue
 */
class RelationValue extends Model
{
    public $table = 'relation_values';

    public static $searchable = [
    ];

    protected $dates = [
        'date_price',
    ];

    protected $fillable = [
    ];

    public function getDatePriceAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function settDatePriceAttribute($value)
    {
        $this->attributes['date_price'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'relation_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
