<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use HasFactory,SoftDeletes;

    public function likeable(): MorphTo
    {
        return $this->morphTo('likeable');
    }

    public function likedByUser()
    {
        return $this->belongsTo(User::class, 'liked_by_user_id');
    }
}
