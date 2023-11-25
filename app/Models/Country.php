<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

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
