<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $name
 * @property string|null $model_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Status modelType(?string $type, bool $withNull = true)
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereModelType($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereUpdatedAt($value)
 * @property string|null $message
 * @method static Builder|Status whereMessage($value)
 * @mixin \Eloquent
 */
class Status extends Model
{
    use HasFactory;

    public function scopeModelType(Builder $builder, ?string $type, bool $withNull = true)
    {
        if (! $type) {
            return $builder->whereNull('model_type');
        }

        if ($withNull) {
            $builder->whereNull('model_type')->orWhere('model_type', $type);
        } else {
            $builder->where('model_type', $type);
        }

        return $builder;
    }
}
