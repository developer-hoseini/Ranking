<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\UserScore
 *
 * @property int $id
 * @property int $user_id
 * @property int $game_id
 * @property int $country_id
 * @property int $is_join
 * @property int $in_club
 * @property int $with_image
 * @property int $team_played
 * @property int $score
 * @property int $win
 * @property int $lose
 * @property int $fault
 * @property int $warning
 * @property string $join_dt
 * @property int $coin
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\Game $game
 * @property-read mixed $country_rank
 * @property-read mixed $rank
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\UserScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereFault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereInClub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereIsJoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereJoinDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereLose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereTeamPlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereWin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore whereWithImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserScore withoutTrashed()
 *
 * @mixin \Eloquent
 */
class UserScore extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'game_id',
        'country_id',
        'is_join',
        'in_club',
        'with_image',
        'team_played',
        'score',
        'win',
    ];

    //    protected $appends = ['rank', 'country_rank'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public static function get_score($user_id, $game_id)
    {
        return self::where(['user_id' => $user_id, 'game_id' => $game_id])->first('score')->score;
    }

    public function getRankAttribute()
    {
        return $this->rank($this->user_id, $this->game_id);
    }

    public function getCountryRankAttribute()
    {
        return $this->country_rank($this->user_id, $this->game_id);
    }

    public static function rank($user_id, $game_id)
    {
        DB::unprepared('SET @rownum := 0;');
        $rank = DB::select('SELECT `rank` FROM
            (SELECT @rownum := @rownum + 1 AS `rank`, `user_id` FROM `user_scores` WHERE `game_id` = :game_id
            ORDER BY `score` DESC, `in_club` DESC, `with_image` DESC, `warning` ASC, `join_dt` ASC) as `result`
            WHERE `user_id` = :user_id',
            ['game_id' => $game_id, 'user_id' => $user_id])[0]->rank;

        if ($rank < 10) {
            $rank = '0'.$rank;
        }

        return $rank;
        // when using "DB::select", you must use:  [0]->object   OR   foreach
    }

    public static function country_rank($user_id, $game_id)
    {
        $user = User::with('profile.state')->where('id', $user_id)->first('id');

        DB::unprepared('SET @rownum := 0;');
        $query = DB::select('SELECT `rank` FROM
            (SELECT @rownum := @rownum + 1 AS `rank`, `user_id` FROM `user_scores` WHERE `game_id` = :game_id AND `country_id` = :country_id
            ORDER BY `score` DESC, `in_club` DESC, `with_image` DESC, `warning` ASC, `join_dt` ASC) as `result`
            WHERE `user_id` = :user_id',
            ['game_id' => $game_id, 'country_id' => $user->profile->state->country_id,
                'user_id' => $user_id]);
        if (! empty($query)) {
            $rank = $query[0]->rank;
            if ($rank < 10) {
                $rank = '0'.$rank;
            }

            return $rank;
        }

        return '';
    }

    public static function rank_between($game_id, $fromm, $to, $country_id)
    {
        if ($country_id == 0) { // global rank
            DB::unprepared('SET @rownum := 0;');

            return DB::select('SELECT `id` FROM
                (SELECT @rownum := @rownum + 1 AS `rank`, `id` FROM `user_score` WHERE `game_id` = :game_id
                ORDER BY `score` DESC, `in_club` DESC, `with_image` DESC, `warning` ASC, `join_dt` ASC) as `result`
                WHERE `rank` between :fromm AND :to ORDER BY `rank` ASC',
                ['game_id' => $game_id, 'fromm' => $fromm, 'to' => $to]);
        } else { // country rank
            DB::unprepared('SET @rownum := 0;');

            return DB::select('SELECT `id` FROM
                (SELECT @rownum := @rownum + 1 AS `rank`, `id` FROM `user_score` WHERE `game_id` = :game_id AND `country_id` = :country_id
                ORDER BY `score` DESC, `in_club` DESC, `with_image` DESC, `warning` ASC, `join_dt` ASC) as `result`
                WHERE `rank` between :fromm AND :to ORDER BY `rank` ASC',
                ['game_id' => $game_id, 'country_id' => $country_id, 'fromm' => $fromm, 'to' => $to]);
        }
    }
}
