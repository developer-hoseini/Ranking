<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory,SoftDeletes;

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
        return $this->belongsTo(Status::class)->modelType(Achievement::class, false);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
