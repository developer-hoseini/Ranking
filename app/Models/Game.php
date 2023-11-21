<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property int $active
 * @property int $is_online
 * @property string|null $image_updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameType> $gameTypes
 * @property-read int|null $game_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invite> $invites
 * @property-read int|null $invites_count
 *
 * @method static Builder|Game active()
 * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
 * @method static Builder|Game newModelQuery()
 * @method static Builder|Game newQuery()
 * @method static Builder|Game query()
 * @method static Builder|Game whereActive($value)
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereId($value)
 * @method static Builder|Game whereImageUpdatedAt($value)
 * @method static Builder|Game whereIsOnline($value)
 * @method static Builder|Game whereName($value)
 * @method static Builder|Game whereSort($value)
 * @method static Builder|Game whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperGame
 */
class Game extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'name',
        'sort',
        'image_updated_at',
        'is_online',
        'active',
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function gameCompetitionsUsers(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->users())->groupBy('users.id');
    }

    public function gameCompetitionsTeams(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->teams())->groupBy('teams.id');
    }

    public function gameCompetitionsGameResults(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->gameResults());
    }

    public function gameCompetitionsScoreOccurredModel(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->scoreOccurredModel())
            ->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'game_id');
    }

    public function gameTypes(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(GameType::class, 'game_type_able');
    }

    public function onlineGames(): HasMany
    {
        return $this->hasMany(OnlineGame::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeGameTypesScope(Builder $query, array $names, ?bool $has = true): Builder
    {
        if (! $has) {
            return $query->whereDoesntHave('gameTypes', function ($q) use ($names) {
                $q->whereIn('name', $names);
            });
        }

        return $query->whereHas('gameTypes', function ($q) use ($names) {
            $q->whereIn('name', $names);
        });
    }

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn () => url('/uploads/games/solo/cover/'.$this->name.'.jpg')
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => url('/uploads/games/solo/icon/'.$this->name.'.jpg')
        );
    }
}
