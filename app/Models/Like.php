<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Like
 *
 * @property int $id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property int|null $liked_by_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $likeable
 * @property-read \App\Models\User|null $likedByUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Like withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Like extends Model
{
    use HasFactory,SoftDeletes;

    public function likeable(): MorphTo
    {
        return $this->morphTo('likeable');
    }

    public function likedByUser()
    {
        return $this->belongsTo(User::class, 'liked_by_user_id');
    }
}
