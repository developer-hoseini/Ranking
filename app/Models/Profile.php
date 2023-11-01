<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $fname
 * @property string|null $lname
 * @property string|null $bio
 * @property int|null $state_id
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property int $gender
 * @property string|null $mobile
 * @property string|null $code_melli
 * @property string|null $en_fullname
 * @property string|null $bank_account
 * @property string|null $account_holder_name
 * @property int|null $show_mobile
 * @property int $likes
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAccountHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCodeMelli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereEnFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereShowMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile withoutTrashed()
 *
 * @property-read \App\Models\State|null $state
 *
 * @mixin \Eloquent
 */
class Profile extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'bio',
        'state_id',
        'birth_date',
        'gender',
        'mobile',
        'code_melli',
        'en_fullname',
        'bank_account',
        'account_holder_name',
        'show_mobile',
        'likes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this?->fname.' '.$this?->lname
        );
    }
}
