<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MApplicationEvent extends Model
{
    use HasFactory, SoftDeletes;

	public $table = 'm_application_events';

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

	public function application()
	{
		return $this->belongsTo(MApplication::class, 'm_application_id');
	}
}
