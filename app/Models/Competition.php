<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use HasFactory,SoftDeletes;

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

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'competitionable')->withPivot(['status_id']);
    }

    public function teams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'competitionable')->withPivot(['status_id']);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameResults(): MorphMany
    {
        return $this->morphMany(GameResult::class, 'gameresultable');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(Competition::class, null);
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
        return $this->morphToMany(Cup::class, 'cupable');
    }

    public function achievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable');
    }

    public function scoreAchievement(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function coinAchievement(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::COIN->value);
    }

    public function scopeStatusTournament(Builder $builder)
    {
        return $builder->whereHas('status', fn ($q) => $q->where('name', StatusEnum::COMPETITION_TOURNAMENT->value));
    }
}
