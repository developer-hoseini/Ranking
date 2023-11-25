<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'state_id', 'address', 'tel', 'is_rezvani', 'sort', 'active',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
