<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Cup
 *
 * @property int $id
 * @property string $name
 * @property int $capacity
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Cup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Cup withoutTrashed()
 * @mixin \Eloquent
 */
class Cup extends Model
{
    use HasFactory,SoftDeletes;

    public function competitions(): MorphToMany
    {
        return $this->morphedByMany(Competition::class, 'cupable')->withPivot(['id', 'step']);
    }
}
