<?php

namespace App\Models;

use App\Enums\StatusEnum;
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

    public function scopeNameScope(Builder $builder, ?string $name)
    {
        $name = StatusEnum::tryFrom($name)?->value;

        if (! $name) {
            return $builder;
        }

        return $builder->where('name', StatusEnum::tryFrom($name)?->value);
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

    protected function isAccepted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->name == StatusEnum::ACCEPTED->value;
            }
        );
    }

    protected function colorClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->name) {
                    StatusEnum::ACCEPTED->value => 'text-success',
                    StatusEnum::PENDING->value => 'text-warning',
                    StatusEnum::REJECTED->value => 'text-danger',
                    StatusEnum::CANCELED->value => 'text-secondary',

                    StatusEnum::GAME_RESULT_WIN->value => 'text-success',
                    StatusEnum::GAME_RESULT_LOSE->value => 'text-danger',
                    StatusEnum::GAME_RESULT_ABSENT->value => 'text-warning',

                    default => '',
                };
            },
        );
    }
}
