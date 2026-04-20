<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use App\Factories\InformationFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;

/**
 * App\Information
 */
class Information extends Model
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'information';

    public static string $prefix = 'INFO_';

    public static string $icon = '/images/information.png';

    public static array $searchable = [
        'name',
        'description',
        'owner',
        'constraints',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'owner',
        'administrator',
        'storage',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'sensitivity',
        'constraints',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return InformationFactory::new();
    }

    /** @return BelongsToMany<Database, $this> */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    /** @return BelongsToMany<Flux, $this> */
    public function fluxes(): BelongsToMany
    {
        return $this->belongsToMany(Flux::class, 'flux_information');
    }


    /**
     * Informations membres de cette catégorie.
     * Une information "catégorie" regroupe plusieurs informations enfants.
     *
     * @return BelongsToMany<Information, $this>
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Information::class,
            'information_information',
            'information_id',
            'child_information_id'
        )->orderBy('name');
    }

    /**
     * Catégories (informations parentes) auxquelles appartient cette information.
     *
     * @return BelongsToMany<Information, $this>
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Information::class,
            'information_information',
            'child_information_id',
            'information_id'
        )->orderBy('name');
    }

    public function graphs(): Collection
    {
        return once(fn() => Graph::query()
            ->select('id','name')
            ->where('class', '=', '2')
            ->whereLike('content', '%"#'.$this->getUID().'"%')
            ->get()
        );
    }

}
