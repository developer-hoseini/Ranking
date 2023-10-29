<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Notify
 *
 * @property int $id
 * @property int $created_by
 * @property string $message
 * @property int|null $country_id
 * @property int|null $club_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club|null $club
 * @property-read \App\Models\Country|null $country
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Notify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify withoutTrashed()
 *
 * @property-read \App\Models\User $createdBy
 *
 * @mixin \Eloquent
 */
class Notify extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'message',
        'country_id',
        'club_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
