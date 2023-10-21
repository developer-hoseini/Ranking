<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
