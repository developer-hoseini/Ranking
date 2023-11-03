<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Achievement
 *
 * @property int $id
 * @property string $achievementable_type
 * @property int $achievementable_id
 * @property string $type
 * @property int $count
 * @property int|null $occurred_model_id
 * @property string|null $occurred_model_type
 * @property int|null $status_id
 * @property int|null $created_by_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $achievmentable
 * @property-read \App\Models\User|null $createdByUser
 * @property-read Model|\Eloquent $occurredModel
 * @property-read \App\Models\Status|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereAchievementableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereAchievementableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereOccurredModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereOccurredModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Achievement extends Model
{
    use SoftDeletes;

    public function achievmentable(): MorphTo
    {
        return $this->morphTo('achievmentable');
    }

    public function occurredModel(): MorphTo
    {
        return $this->morphTo('occurredModel');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(__CLASS__, false);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
