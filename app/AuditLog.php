<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

/**
 * App\AuditLog
 *
 * @property int $id
 * @property string $description
 * @property int|null $subject_id
 * @property string|null $subject_type
 * @property int|null $user_id
 * @property \Illuminate\Support\Collection|null $properties
 * @property string|null $host
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLog whereUserId($value)
 * @mixin \Eloquent
 */
class AuditLog extends Model
{
    public $table = 'audit_logs';

    protected $fillable = [
        'description',
        'subject_id',
        'subject_type',
        'user_id',
        'properties',
        'host',
    ];

    protected $casts = [
        'properties' => 'collection',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
