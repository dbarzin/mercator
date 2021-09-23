<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Annuaire
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $solution
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $zone_admin_id
 *
 * @property-read \App\ZoneAdmin|null $zone_admin
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire newQuery()
 * @method static \Illuminate\Database\Query\Builder|Annuaire onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereSolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Annuaire whereZoneAdminId($value)
 * @method static \Illuminate\Database\Query\Builder|Annuaire withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Annuaire withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Annuaire extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'annuaires';

    public static $searchable = [
        'name',
        'description',
        'solution',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'solution',
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function zone_admin()
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
