<?php

namespace App;

use App\Traits\Auditable;
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
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'source_id',
        'destination_id',
    ];

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
