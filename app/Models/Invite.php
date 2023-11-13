<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * App\Models\Invite
 *
 * @property int $id
 * @property int $inviter_user_id
 * @property int $invited_user_id
 * @property int $game_id
 * @property int|null $game_type_id
 * @property int|null $club_id
 * @property int|null $game_status_id
 * @property int|null $confirm_status_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club|null $club
 * @property-read \App\Models\Status|null $confirmStatus
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Status|null $gameStatus
 * @property-read \App\Models\GameType|null $gameType
 * @property-read \App\Models\User $invitedUser
 * @property-read \App\Models\User $inviterUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereConfirmStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereGameStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereGameTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereInvitedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereInviterUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Invite extends Model
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'inviter_user_id', 'invited_user_id', 'game_id', 'game_type_id', 'club_id', 'game_status_id', 'confirm_status_id',
    ];

    public function gameStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'game_status_id')->modelType(Invite::class, false);
    }

    public function confirmStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'confirm_status_id')->modelType(null);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameType(): MorphToMany
    {
        return $this->morphToMany(GameType::class, 'game_type_able');
    }

    public function inviterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_user_id');
    }

    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function competitions(): MorphToMany
    {
        return $this->morphToMany(Competition::class, 'competitionable')->withPivot(['status_id']);
    }

    public function inviteCompetitionsGameResults(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->gameResults());
    }

    public function inviteCompetitionsScoreOccurredModel(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->scoreOccurredModel());
    }
}
