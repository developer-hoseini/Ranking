<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Club
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property string|null $address
 * @property string|null $tel
 * @property int $is_rezvani
 * @property int $sort
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\State $state
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Club newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club query()
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereIsRezvani($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Club extends Model
{
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
