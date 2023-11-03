<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Gallery
 *
 * @property int $id
 * @property string $path
 * @property string|null $href
 * @property string|null $name
 * @property string|null $type
 * @property int|null $sort
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Gallery active(bool $isActive = true)
 * @method static Builder|Gallery newModelQuery()
 * @method static Builder|Gallery newQuery()
 * @method static Builder|Gallery onlyTrashed()
 * @method static Builder|Gallery query()
 * @method static Builder|Gallery typeSlider()
 * @method static Builder|Gallery whereActive($value)
 * @method static Builder|Gallery whereCreatedAt($value)
 * @method static Builder|Gallery whereDeletedAt($value)
 * @method static Builder|Gallery whereHref($value)
 * @method static Builder|Gallery whereId($value)
 * @method static Builder|Gallery whereName($value)
 * @method static Builder|Gallery wherePath($value)
 * @method static Builder|Gallery whereSort($value)
 * @method static Builder|Gallery whereType($value)
 * @method static Builder|Gallery whereUpdatedAt($value)
 * @method static Builder|Gallery withTrashed()
 * @method static Builder|Gallery withoutTrashed()
 *
 * @mixin \Eloquent
 */
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
