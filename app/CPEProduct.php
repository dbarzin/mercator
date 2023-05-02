<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CPEProduct
 */
class CPEProduct extends Model
{
    public $table = 'cpe_products';

    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'cpe_vendor_id',
        'name',
    ];

    public function versions()
    {
        return $this->belongsToMany(CPEVersion::class)->orderBy('name');
    }

    public function vendor()
    {
        return $this->belongsTo(CPEVendor::class, 'cpe_vendor_id');
    }
}
