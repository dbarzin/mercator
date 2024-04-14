<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Relation
 */
class Relation extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'relations';

    public static $searchable = [
        'name',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'attributes',
        'reference',
        'responsible',
        'order_number',
        'active',
        'start_date',
        'end_date',
        'comments',
        'importance',
        'source_id',
        'destination_id',
        'is_hierarchical',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function source()
    {
        return $this->belongsTo(Entity::class, 'source_id')->orderBy('name');
    }

    public function destination()
    {
        return $this->belongsTo(Entity::class, 'destination_id')->orderBy('name');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public function values()
    {
        return $this->hasMany(RelationValue::class, 'relation_id', 'id')->orderBy('date_price');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
