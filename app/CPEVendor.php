<?php

namespace App;

use App\CPEProduct;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CPEVendor
 *
 */
class CPEVendor extends Model
{
    public $table = 'cpe_vendors';
    
    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'part',
        'name'
    ];

    public function products()
    {
        return $this->belongsToMany(CPEProduct::class)->orderBy('name');
    }

}
