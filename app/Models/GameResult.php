<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GameResult
 *
 * @property int $id
 * @property int $invite_id
 * @property int|null $inviter_game_result_status_id
 * @property int|null $invited_game_result_status_id
 * @property int $club_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club $club
 * @property-read \App\Models\Invite $invite
 * @property-read \App\Models\Status|null $invitedGameResultStatus
 * @property-read \App\Models\Status|null $inviterGameResultStatus
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereInviteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereInvitedGameResultStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereInviterGameResultStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult withoutTrashed()
 *
 * @property-read \App\Models\Status|null $gameResultStatus
 * @property-read Model|\Eloquent $gameresultable
 * @property-read Model|\Eloquent $playerable
 * @property-read \App\Models\Status $status
 * @property string $playerable_type
 * @property int $playerable_id
 * @property string $gameresultable_type
 * @property int $gameresultable_id
 * @property int $game_result_status_id
 * @property int|null $status_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereGameResultStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereGameresultableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereGameresultableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult wherePlayerableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult wherePlayerableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameResult whereStatusId($value)
 *
 * @mixin \Eloquent
 */
class GameResult extends Model
{
    use HasFactory, SoftDeletes;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(null, false);
    }

    public function gameResultStatus()
    {
        return $this->belongsTo(Status::class, 'game_result_status_id')->modelType(GameResult::class, false);

    }

    public function playerable(): MorphTo
    {
        return $this->morphTo('playerable');
    }

    public function gameresultable(): MorphTo
    {
        return $this->morphTo('gameresultable');
    }
}
