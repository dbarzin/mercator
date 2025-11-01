<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\CPEVendor
 */
class CPEVendor extends Model
{
    use HasFactory;

    public $table = 'cpe_vendors';

    public $timestamps = false;

    public static array $searchable = [
    ];

    protected array $dates = [
    ];

    protected $fillable = [
        'part',
        'name',
    ];

    /** @return BelongsToMany<CPEProduct, self> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(CPEProduct::class)->orderBy('name');
    }
}
