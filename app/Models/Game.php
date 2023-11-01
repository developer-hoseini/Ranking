<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property int $active
 * @property int $is_online
 * @property string|null $image_updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereImageUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 *
 * @property int $coin
 * @property int $score
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereScore($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invite> $invites
 * @property-read int|null $invites_count
 *
 * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Game active()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 *
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

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

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'game_id');
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
