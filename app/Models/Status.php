<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


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
