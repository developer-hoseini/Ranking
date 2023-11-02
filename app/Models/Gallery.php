<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Gallery extends Model
{
    use HasFactory,SoftDeletes;

    public function scopeTypeSlider(Builder $builder)
    {
        $builder->where('type', 'slider');
    }

    public function scopeActive(Builder $builder, bool $isActive = true)
    {
        $builder->where('active', $isActive);
    }
}
