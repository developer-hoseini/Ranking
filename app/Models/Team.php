<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(null);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function capitan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'capitan_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['created_at', 'updated_at']);
    }
}
