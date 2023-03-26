<?php

namespace App;

use App\CPEProduct;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CPEProduct
 *
 */
class CPEVersion extends Model
{
    public $table = 'cpe_versions';

    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'cpe_product_id',
        'name'
    ];

    public function product()
    {
        return $this->belongsTo(CPEProduct::class, 'cpe_product_id');
    }
}
