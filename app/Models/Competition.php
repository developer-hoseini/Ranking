<?php

namespace App\Models;

use App\Enums\AchievementTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Competition
 *
 * @property int $id
 * @property string $name
 * @property int $coin
 * @property int $capacity
 * @property string|null $description
 * @property int $game_id
 * @property int $state_id
 * @property int $status_id
 * @property int $created_by_user_id
 * @property string|null $end_register_at
 * @property string|null $start_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $createdByUser
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\State $state
 * @property-read \App\Models\Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Competition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition query()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereEndRegisterAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Competition withoutTrashed()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cup> $cups
 * @property-read int|null $cups_count
 * @property-read \App\Models\Team|null $team
 *
 * @method static \Database\Factories\CompetitionFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Competition extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'coin',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(null);
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

    public function achievementScore(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function achievementCoin(): MorphOne
    {
        return $this->morphOne(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::COIN->value);
    }
}
