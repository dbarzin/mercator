<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

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
        'inportance',
        'source_id',
        'destination_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function source()
    {
        return $this->belongsTo(Entity::class, 'source_id');
    }

    public function destination()
    {
        return $this->belongsTo(Entity::class, 'destination_id');
    }
}
