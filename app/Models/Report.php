<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Report
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Report withoutTrashed()
 *
 * @property int $id
 * @property int $reporter_id
 * @property int $reported_id
 * @property string $reason
 * @property int $status_id
 * @property int $is_team
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereIsTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reporter_id',
        'reported_id',
        'reason',
        'status_id',
        'is_team',
    ];
}
