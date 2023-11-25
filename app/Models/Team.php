<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends Model implements HasAvatar, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public function teamStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(null);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function capitan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'capitan_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['created_at', 'updated_at']);
    }

    public function competitions(): MorphToMany
    {
        return $this->morphToMany(Competition::class, 'competitionable')->withPivot(['status_id']);
    }

    public function achievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable');
    }

    public function teamScoreAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function teamCoinAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::COIN->value);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function teamInvites(): MorphMany
    {
        return $this->morphMany(Invite::class, 'inviteable');
    }

    public function scopeAuthCreatedScope(Builder $builder): Builder
    {
        return $builder->whereHas('createdByUser', fn ($q) => $q->authScope());
    }

    public function scopeAcceptedScope(Builder $builder): Builder
    {
        return $builder->whereHas('teamStatus', fn ($q) => $q->nameScope(StatusEnum::ACCEPTED->value));
    }

    public function teamCups(): MorphToMany
    {
        return $this->morphToMany(Cup::class, 'cupable');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatar')?->first()?->getUrl() ?? '';
    }

    //for media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])
            ->singleFile();
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avatar = $this->getFirstMediaUrl('avatar');

                if ($avatar) {
                    return $avatar;
                }

                return asset('assets/images/default-team-profile.png');
            }
        );
    }

    protected function avatarName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->name ?? "team-doesn't-have-name";
            }
        );
    }

    protected function teamRank(): Attribute
    {
        return Attribute::make(
            get: function () {

                $columnNameTeamRank = 'this_team_rank';
                $columnsNeedForSubQuery = ['id', 'deleted_at'];
                $columnScoreAchievementsSum = 'team_score_achievements_sum_count';

                $sub1 = Team::query()
                    ->where('game_id', $this->game_id)
                    ->select($columnsNeedForSubQuery)
                    ->withSum("teamScoreAchievements as $columnScoreAchievementsSum", 'count');

                $sub2 = Team::query()
                    ->fromSub($sub1->getQuery(), 'teams_a')
                    ->select([
                        ...$columnsNeedForSubQuery,
                        DB::raw("ROW_NUMBER() OVER (ORDER BY $columnScoreAchievementsSum desc) as $columnNameTeamRank"),
                    ])
                    ->latest('id');

                $team = Team::query()
                    ->fromSub($sub2->getQuery(), 'teams')
                    ->where('id', $this->id)
                    ->select("$columnNameTeamRank")
                    ->first();

                return $team?->$columnNameTeamRank ?? '-';
            }
        );
    }
}
