<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'likeable_type', 'likeable_id', 'liked_by_user_id',
    ];

    public function likeable(): MorphTo
    {
        return $this->morphTo('likeable');
    }

    public function likedByUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'liked_by_user_id');
    }
}
