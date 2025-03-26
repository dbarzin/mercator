<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\CPEProduct
 */
class CPEVersion extends Model
{
    use HasFactory;
    
    public $table = 'cpe_versions';

    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'cpe_product_id',
        'name',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(CPEProduct::class, 'cpe_product_id');
    }
}
