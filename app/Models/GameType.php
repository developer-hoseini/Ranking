<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GameType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameType query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameType extends Model
{
    use HasFactory;
}
