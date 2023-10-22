<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @mixin \Eloquent
 */
class GameResult extends Model
{
    use HasFactory, SoftDeletes;

    public function invite(): BelongsTo
    {
        return $this->belongsTo(Invite::class);
    }

    public function inviterGameResultStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'inviter_game_result_status_id')->modelType(GameResult::class, false);
    }

    public function invitedGameResultStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'invited_game_result_status_id')->modelType(GameResult::class, false);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
