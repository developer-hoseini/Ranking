<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

class GameResult extends Model
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function gameResultUserStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'user_status_id')->modelType(null, false);
    }

    public function gameResultAdminStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'admin_status_id')->modelType(null, false);
    }

    public function gameResultStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'game_result_status_id')->modelType(__CLASS__, false);
    }

    public function playerable(): MorphTo
    {
        return $this->morphTo('playerable');
    }

    public function gameresultable(): MorphTo
    {
        return $this->morphTo('gameresultable');
    }

    public function gameresultableCompetition(): MorphTo
    {
        return $this->morphTo('gameresultable')->where('gameresultable_type', Competition::class);
    }

    public function gameResultCompetition(): HasOneDeep
    {
        return $this->hasOneDeep(
            Competition::class,
            [__CLASS__],
            ['id', 'id'],
            [null, null, 'gameresultable_id']
        )->where('gameresultable_type', Competition::class);
    }

    public function gameResultCompetitionInvite(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->gameResultCompetition(), (new Competition)->invite());
    }

    public function gameResultCompetitionInviteGameType(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->gameResultCompetitionInvite(), (new Invite())->gameType());
    }
}
