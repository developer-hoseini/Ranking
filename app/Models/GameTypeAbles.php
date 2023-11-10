<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GameTypeAbles extends Model
{
    public $timestamps = false;

    public function gameTypeAble(): MorphTo
    {
        return $this->morphTo();
    }

    public function gameType(): BelongsTo
    {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }
}
