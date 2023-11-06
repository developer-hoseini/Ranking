<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

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
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public $timestamps = false;

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function competitions(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->states(), (new State())->competition());
    }

    public function countryCompetitionsUsers(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->users());
    }

    public function countryCompetitionsScoreOccurredModel(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->scoreOccurredModel())
            ->where('type', AchievementTypeEnum::SCORE->value);
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('assets/img/flags/'.str_replace(' ', '-', $this?->name).'.png')
        );
    }
}
