<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RelationValue
 */
class RelationValue extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    public $table = 'relation_values';

    public static $searchable = [
    ];

    // For PostgreSQL
    protected $primaryKey = null;

    protected $dates = [
        'date_price',
    ];

    protected $fillable = [
    ];

    public function getDatePriceAttribute($value)
    {
        return $value;
    }

    public function setDatePriceAttribute($value): void
    {
        $this->attributes['date_price'] = $value;
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'relation_id');
    }
}
