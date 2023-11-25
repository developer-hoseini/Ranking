<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reporter_id',
        'reported_id',
        'reason',
        'status_id',
        'is_team',
    ];
}
