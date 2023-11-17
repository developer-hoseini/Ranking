<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
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

/**
 * App\Models\Team
 *
 * @property int $id
 * @property string $name
 * @property string|null $about
 * @property int|null $likes
 * @property int|null $status_id
 * @property int|null $capitan_user_id
 * @property int|null $state_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $achievements
 * @property-read int|null $achievements_count
 * @property-read \App\Models\User|null $capitan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\Status|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCapitanUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $coinAchievements
 * @property-read int|null $coin_achievements_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $scoreAchievements
 * @property-read int|null $score_achievements_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Team onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Team withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Team withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Team extends Model implements HasAvatar, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(null);
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

    public function scoreAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function coinAchievements(): MorphMany
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

    public function scopeAuthCreatedScope(Builder $builder): Builder
    {
        return $builder->whereHas('createdByUser', fn ($q) => $q->authScope());
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

    protected function teamRank(): Attribute
    {
        return Attribute::make(
            get: function () {

                $columnNameTeamRank = 'this_team_rank';
                $columnsNeedForSubQuery = ['id', 'deleted_at'];
                $columnScoreAchievementsSum = 'score_achievements_sum_count';

                $sub1 = Team::query()
                    ->where('game_id', $this->game_id)
                    ->select($columnsNeedForSubQuery)
                    ->withSum("scoreAchievements as $columnScoreAchievementsSum", 'count');

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
