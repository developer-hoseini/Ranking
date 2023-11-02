<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes;

    public function achievmentable(): MorphTo
    {
        return $this->morphTo('achievmentable');
    }

    public function occurredModel(): MorphTo
    {
        return $this->morphTo('occurredModel');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(__CLASS__, false);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
