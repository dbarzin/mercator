<?php

namespace App;

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
        return $value;
    }

    public function setDatePriceAttribute($value)
    {
        $this->attributes['date_price'] = $value;
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
