<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MApplicationLog extends Model
{
    use HasFactory, SoftDeletes;

	public $table = 'm_application_logs';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'cartographer_id',
        'm_application_id',
        'message',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
