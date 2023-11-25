<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

class Achievement extends Model
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;

    protected $fillable = [
        'achievementable_type',
        'achievementable_id',
        'type',
        'count',
        'occurred_model_id',
        'occurred_model_type',
        'status_id',
        'created_by_user_id',
    ];

    public function achievementable(): MorphTo
    {
        return $this->morphTo('achievementable');
    }

    public function occurredModel(): MorphTo
    {
        return $this->morphTo('occurredModel');
    }

    public function occurredModelCompetition(): MorphTo
    {
        return $this->morphTo('occurredModel')->where('occurred_model_type', Competition::class);
    }

    public function achievementCompetition(): HasOneDeep
    {
        return $this->hasOneDeep(
            Competition::class,
            [__CLASS__],
            ['id', 'id'],
            [null, null, 'occurred_model_id']
        )->where('occurred_model_type', Competition::class);
    }

    public function achievementCompetitionState(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->achievementCompetition(), (new Competition())->state());
    }

    public function achievementCompetitionStateCountry(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->achievementCompetitionState(), (new State())->country());
    }

    public function achievementStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(__CLASS__, false);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function scopeCompletedProfileScope(Builder $query, $is = true): Builder
    {
        if ($is) {
            return $query->whereHas('achievementStatus', fn ($q) => $q->where('name', StatusEnum::ACHIEVEMENT_COMPLETE_PROFILE->value));
        }

        return $query->whereDoesntHave('achievementStatus', fn ($q) => $q->where('name', StatusEnum::ACHIEVEMENT_COMPLETE_PROFILE->value));

    }
}
