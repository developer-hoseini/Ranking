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

    public function cupStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(null, true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);
    }

    public function scopeCupAcceptedStatusScope(Builder $builder)
    {
        return $builder->whereHas('cupStatus', fn ($q) => $q->nameScope(StatusEnum::ACCEPTED->value));
    }

    public function scopeTeamScope(Builder $builder, $isTeam = true)
    {
        return $builder->where('is_team', $isTeam);
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
                $isCapacityAvailable = $this->competitions->count() < $this->capacity / 2;
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

                if (! $this->is_team) {
                    $isRegistered = $this->registeredUsers->where('id', $authUser->id)->first();
                } else {
                    $isRegistered = $this->registeredTeams->whereIn('id', $authUser->teams()->acceptedScope()->pluck('id'))->first();
                }

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
                ->where('gameResultAdminStatus.name', StatusEnum::ACCEPTED->value)
                ->where('gameResultStatus.name', StatusEnum::GAME_RESULT_WIN->value)
                ->first();

            $loserGameResult = $competition->gameResults
                ->where('gameResultAdminStatus.name', StatusEnum::ACCEPTED->value)
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
