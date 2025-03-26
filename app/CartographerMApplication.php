<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartographerMApplication extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'cartographer_m_application';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'm_application_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
