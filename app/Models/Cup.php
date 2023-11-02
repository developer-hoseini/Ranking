<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cup extends Model
{
    use HasFactory,SoftDeletes;

    public function competitions(): MorphToMany
    {
        return $this->morphedByMany(Competition::class, 'cupable')->withPivot(['id', 'step']);
    }
}
