<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

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
 *
 * @method static \Database\Factories\CupFactory factory($count = null, $state = [])
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
 *
 * @property-read \App\Models\User $createdByUser
 * @property-read \App\Models\Game $game
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $registeredTeams
 * @property-read int|null $registered_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $registeredUsers
 * @property-read int|null $registered_users_count
 * @property-read \App\Models\State $state
 *
 * @mixin \Eloquent
 */
class Cup extends Model implements HasMedia
{
    use HasFactory,SoftDeletes;
    use InteractsWithMedia;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $cast = [
        'start_at' => 'datetime:Y-m-d',
        'end_register_at' => 'datetime:Y-m-d',
    ];

    public function competitions(): MorphToMany
    {
        return $this->morphedByMany(Competition::class, 'cupable')->withPivot(['id', 'step']);
    }

    public function registeredUsers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'cupable');
    }

    public function registeredTeams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'cupable');
    }

    public function cupRegisteredTeamsUsers(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->registeredTeams(), (new Team())->users())->groupBy('users.id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cupCompetitionsGame(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->competitions(), (new Competition())->game());
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(null, true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);
    }

    public function scopeAcceptedStatusScope(Builder $builder)
    {
        return $builder->whereHas('status', fn ($q) => $q->nameScope(StatusEnum::ACCEPTED->value));
    }

    protected function startAtDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Carbon::parse($this->start_at)->format('Y-m-d');
            }

        );
    }

    protected function endRegisterAtDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Carbon::parse($this->end_register_at)->format('Y-m-d');
            }

        );
    }

    protected function isRegisterable(): Attribute
    {
        return Attribute::make(
            get: function () {
                $isCapacityAvailable = $this->competitions->count() < $this->capacity;
                $hasTimeToRegister = $this->end_register_at > now();

                return $isCapacityAvailable && $hasTimeToRegister;
            }
        );
    }

    protected function isAuthUserParticipate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $authUser = auth()?->user();

                if (! $authUser) {
                    return false;
                }

                $isRegistered = $this->registeredUsers->where('id', $authUser->id)->first();

                return $isRegistered ? true : false;

            }
        );
    }

    protected function step(): Attribute
    {
        return Attribute::make(
            get: function () {
                $step = 0;
                $capacity = $this->capacity;

                while ($capacity >= 2) {
                    $step++;
                    $capacity = $capacity / 2;
                }

                return $step;
            }
        );
    }

    protected function isFinished(): Attribute
    {
        return Attribute::make(
            get: function () {
                $isFinished = false;

                $finalCompetition = $this->competitions->where('pivot.step', '=', $this->step)->first();

                if ($finalCompetition) {
                    $hasResult = $finalCompetition->gameResults->count() > 0;
                    if ($hasResult) {
                        $isFinished = true;
                    }
                }

                return $isFinished;
            }
        );
    }

    private function getCompetitionResult($competition)
    {
        // first winer , second loser
        $results = [null, null];

        if ($competition) {
            $winGameResult = $competition->gameResults
                ->where('status.name', StatusEnum::ACCEPTED->value)
                ->where('gameResultStatus.name', StatusEnum::GAME_RESULT_WIN->value)
                ->first();

            $loserGameResult = $competition->gameResults
                ->where('status.name', StatusEnum::ACCEPTED->value)
                ->where('gameResultStatus.name', StatusEnum::GAME_RESULT_LOSE->value)
                ->first();

            if ($winGameResult) {
                $results[0] = $winGameResult->playerable;
            }

            if ($loserGameResult) {
                $results[1] = $loserGameResult->playerable;
            }
        }

        return $results;
    }

    protected function finalResults(): Attribute
    {
        return Attribute::make(
            get: function () {
                $competition = $this->competitions->where('pivot.step', '=', $this->step)->first();
                $finalResults = $this->getCompetitionResult($competition);

                return $finalResults;
            }
        );
    }

    protected function semiFinalLosersResults(): Attribute
    {
        return Attribute::make(
            get: function () {
                /* step -1 mean semiFinal competition losers for rank 3 and 4 */
                $competition = $this->competitions->where('pivot.step', '=', '-1')->first();
                $semiFinalLoserResults = $this->getCompetitionResult($competition);

                return $semiFinalLoserResults;
            }
        );
    }

    protected function cupFirstAndSecondUsers(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->finalResults;
            }
        );
    }

    protected function cupThirdAndForthUser(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->semiFinalLosersResults;
            }
        );
    }
}
