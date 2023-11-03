<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $sortname
 * @property string $name
 * @property int|null $phonecode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\State> $states
 * @property-read int|null $states_count
 *
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhonecode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereSortname($value)
 *
 * @mixin \Eloquent
 */
class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
