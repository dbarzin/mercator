<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Graph
 */
class Graph extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'graphs';

    public static $searchable = [
        'name',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'content',
    ];
}
