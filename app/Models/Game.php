<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Game extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'name',
        'sort',
        'image_updated_at',
        'is_online',
        'active',
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function gameCompetitionsUsers()
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->users());
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'game_id');
    }

    public function gameTypes(): BelongsToMany
    {
        return $this->belongsToMany(GameType::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn () => url('/uploads/games/solo/cover/'.$this->name.'.jpg?'.strtotime($this->image_updated))
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => url('/uploads/games/solo/icon/'.$this->name.'.jpg?'.strtotime($this->image_updated))
        );
    }
}
