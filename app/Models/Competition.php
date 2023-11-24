<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use App\Enums\CompetitionableType;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Competition
 *
 * @property int $id
 * @property string $name
 * @property int $capacity
 * @property string|null $description
 * @property int $game_id
 * @property int $state_id
 * @property int $status_id
 * @property int|null $created_by_user_id
 * @property string|null $end_register_at
 * @property string|null $start_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $achievements
 * @property-read int|null $achievements_count
 * @property-read \App\Models\Achievement|null $coinAchievement
 * @property-read \App\Models\User|null $createdByUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cup> $cups
 * @property-read int|null $cups_count
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameResult> $gameResults
 * @property-read int|null $game_results_count
 * @property-read \App\Models\Achievement|null $scoreAchievement
 * @property-read \App\Models\State $state
 * @property-read \App\Models\Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\CompetitionFactory factory($count = null, $state = [])
 * @method static Builder|Competition newModelQuery()
 * @method static Builder|Competition newQuery()
 * @method static Builder|Competition onlyTrashed()
 * @method static Builder|Competition query()
 * @method static Builder|Competition statusTournament()
 * @method static Builder|Competition whereCapacity($value)
 * @method static Builder|Competition whereCreatedAt($value)
 * @method static Builder|Competition whereCreatedByUserId($value)
 * @method static Builder|Competition whereDeletedAt($value)
 * @method static Builder|Competition whereDescription($value)
 * @method static Builder|Competition whereEndRegisterAt($value)
 * @method static Builder|Competition whereGameId($value)
 * @method static Builder|Competition whereId($value)
 * @method static Builder|Competition whereName($value)
 * @method static Builder|Competition whereStartAt($value)
 * @method static Builder|Competition whereStateId($value)
 * @method static Builder|Competition whereStatusId($value)
 * @method static Builder|Competition whereUpdatedAt($value)
 * @method static Builder|Competition withTrashed()
 * @method static Builder|Competition withoutTrashed()
 *
 * @property-read \App\Models\Achievement|null $coinOccurredModel
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Achievement|null $scoreOccurredModel
 *
 * @mixin \Eloquent
 */
class Competition extends Model implements HasMedia
{
    use HasFactory,SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'game_id',
        'state_id',
        'status_id',
        'created_by_user_id',
        'end_register_at',
        'start_at',
    ];

    public function competitionables(): BelongsToMany
    {
        return $this->belongsToMany(Competitionable::class, 'competition_id');
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'competitionable')
            ->withPivot(['status_id', 'type'])
            ->wherePivot('type', CompetitionableType::PLAYER->value);
    }

    public function opponentUsers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'competitionable')
            ->withPivot(['status_id'])
            ->whereNot('users.id', \Auth::user()?->id);
    }

    public function competitionAgents(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'competitionable')
            ->withPivot(['status_id', 'type'])
            ->wherePivot('type', CompetitionableType::AGENT->value);
    }

    public function teams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'competitionable')
            ->withPivot(['status_id', 'type'])
            ->wherePivot('type', CompetitionableType::PLAYER->value);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameResults(): MorphMany
    {
        return $this->morphMany(GameResult::class, 'gameresultable');
    }

    public function competitionStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(__CLASS__, null);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function cups(): MorphToMany
    {
        return $this->morphToMany(Cup::class, 'cupable')->withPivot(['step']);
    }

    public function achievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable');
    }

    public function competitionScoreAchievement(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function competitionCoinAchievement(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::COIN->value);
    }

    public function scoreOccurredModel(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'occurred_model')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function coinOccurredModel(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'occurred_model')->where('type', AchievementTypeEnum::COIN->value);
    }

    public function scopeStatusTournament(Builder $builder): Builder
    {
        return $builder->whereHas('competitionStatus', fn ($q) => $q->where('name', StatusEnum::COMPETITION_TOURNAMENT->value));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);
    }

    protected function loserPlayer(): Attribute
    {
        return Attribute::make(
            get: function () {
                $gameResult = $this->gameResults->where('gameResultStatus.name', StatusEnum::GAME_RESULT_LOSE->value)->first();
                if ($gameResult) {

                    if ($gameResult->playerable_type == User::class) {
                        return $this->users->where('id', $gameResult->playerable_id)->first();
                    }

                    if ($gameResult->playerable_type == Team::class) {
                        return $this->teams->where('id', $gameResult->playerable_id)->first();
                    }

                }

                return null;
            }
        );
    }

    protected function winerPlayer(): Attribute
    {
        return Attribute::make(
            get: function () {
                $gameResult = $this->gameResults->where('gameResultStatus.name', StatusEnum::GAME_RESULT_WIN->value)->first();

                if ($gameResult) {
                    if ($gameResult->playerable_type == User::class) {
                        return $this->users->where('id', $gameResult->playerable_id)->first();
                    }

                    if ($gameResult->playerable_type == Team::class) {
                        return $this->teams->where('id', $gameResult->playerable_id)->first();
                    }
                }

                return null;
            }
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function () {
                $image = $this->getFirstMediaUrl('images');

                return $image;
            }
        );
    }

    protected function isRegisterable(): Attribute
    {
        return Attribute::make(
            get: function () {
                $isCapacityAvailable = $this->capacity < ($this->users->count() + $this->teams->count());
                $isNotEnd = $this->end_register_at < now();

                return $isCapacityAvailable && $isNotEnd;
            }
        );
    }

    protected function isFinished(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hasGameResult = $this->gameResults->where('gameResultAdminStatus.name', StatusEnum::ACCEPTED->value)->first();

                return $hasGameResult ? true : false;
            }
        );
    }
}
