<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoinRequest extends Model
{
    use HasFactory,SoftDeletes;

    public function coinRequestStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(null, true);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
