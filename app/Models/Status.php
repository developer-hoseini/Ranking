<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $name
 * @property string|null $model_type
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\StatusFactory factory($count = null, $state = [])
 * @method static Builder|Status modelType(?string $type, ?bool $withNull = true)
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereMessage($value)
 * @method static Builder|Status whereModelType($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Status extends Model
{
    use HasFactory;

    public function scopeModelType(Builder $builder, ?string $type, ?bool $withNull = true)
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

    protected function nameWithoutModelPrefix(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->model_type) {
                    Achievement::class => str_replace('achievement_', '', $this->name),
                    Ticket::class => str_replace('ticket_', '', $this->name),
                    GameResult::class => str_replace('game_result_', '', $this->name),
                    default => $this->name,
                };
            },
        );
    }
}
