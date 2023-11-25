<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class Invite extends Model
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'inviter_user_id', 'invited_user_id', 'game_id', 'game_type_id', 'club_id', 'game_status_id', 'confirm_status_id',
    ];

    public function gameStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'game_status_id')->modelType(__CLASS__, false);
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

    public function inviteable(): MorphTo
    {
        return $this->morphTo('inviteable');
    }
}
