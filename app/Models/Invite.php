<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Invite extends Model
{
    use HasFactory,SoftDeletes;

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

    public function gameType(): BelongsTo
    {
        return $this->belongsTo(GameType::class);
    }

    public function inviterUser()
    {
        return $this->belongsTo(User::class, 'inviter_user_id');
    }

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
