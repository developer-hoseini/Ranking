<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;


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
