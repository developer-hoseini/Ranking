<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notify extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'message',
        'country_id',
        'club_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
