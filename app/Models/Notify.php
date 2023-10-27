<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
