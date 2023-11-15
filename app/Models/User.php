<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\AchievementTypeEnum;
use App\Enums\CoinRequestTypeEnum;
use App\Enums\StatusEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $achievements
 * @property-read int|null $achievements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $coinAchievements
 * @property-read int|null $coin_achievements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achievement> $scoreAchievements
 * @property-read int|null $score_achievements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User onlyTrashed()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User roleUser()
 * @method static Builder|User whereActive($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likesBy
 * @property-read int|null $likes_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cup> $cups
 * @property-read int|null $cups_count
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements CanResetPassword, FilamentUser, HasAvatar, HasMedia, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles,Notifiable;
    use InteractsWithMedia,SoftDeletes;
    use PasswordsCanResetPassword;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'active',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot(['created_at', 'updated_at']);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function competitions(): MorphToMany
    {
        return $this->morphToMany(Competition::class, 'competitionable');
    }

    public function cups(): MorphToMany
    {
        return $this->morphToMany(Cup::class, 'cupable');
    }

    public function userCompetitionsGame(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->competitions(), (new Competition)->game());
    }

    public function achievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likesBy(): HasMany
    {
        return $this->hasMany(Like::class, 'liked_by_user_id');
    }

    public function scoreAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function coinAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::COIN->value);
    }

    public function coinRequests(): HasMany
    {
        return $this->hasMany(CoinRequest::class, 'created_by_user_id');
    }

    public function inviter(): HasMany
    {
        return $this->hasMany(Invite::class, 'inviter_user_id');
    }

    public function invited(): HasMany
    {
        return $this->hasMany(Invite::class, 'invited_user_id');
    }

    public function gameResults(): MorphMany
    {
        return $this->morphMany(GameResult::class, 'playerable_type');
    }

    //for panel
    public function canAccessPanel(Panel $panel): bool
    {
        return Auth::user()?->hasRole(['admin']) && Auth::user()?->active;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatar')?->first()?->getUrl() ?? '';
    }

    //for media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])
            ->singleFile();
    }

    //scope
    public function scopeRoleUser(Builder $builder): Builder
    {
        return $builder->whereHas('roles', function (Builder $query) {
            $query->where('name', 'user');
        });

    }

    public function scopeSearchUserName(Builder $builder, string $username): Builder
    {

        $fullNameFa = str_replace(['ك', 'ي'], ['ک', 'ی'], $username);
        $fullNameAr = str_replace(['ک', 'ی'], ['ك', 'ي'], $username);

        return $builder->whereHas('profile', function ($query) use ($fullNameFa, $fullNameAr) {
            $query->where(DB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%'.$fullNameFa.'%')
                ->orWhere(DB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%'.$fullNameAr.'%');
        })->orWhere([
            ['username', 'LIKE', '%'.$fullNameFa.'%'],
            ['username', 'LIKE', '%'.$fullNameAr.'%'],
        ]);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function scopeSearchProfileAvatarNameScope(Builder $builder, $search): Builder
    {
        return $builder->whereHas('profile', function (Builder $query) use ($search) {
            $query->where('avatar_name', 'like', '%'.$search.'%');
        });

    }

    public function scopeAuthScope(Builder $builder): Builder
    {
        return $builder->where('users.id', auth()->id());
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avatar = $this->getFirstMediaUrl('avatar');

                if ($avatar) {
                    return $avatar;
                }

                return asset('assets/images/default-profile.png');
            }
        );
    }

    protected function isProfileCompleted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return (bool) $this->profile?->avatar_name;
            }
        );
    }

    protected function sumCoinAchievements(): Attribute
    {
        return Attribute::make(
            get: function () {
                $count = $this->achievements()
                    ->where('type', AchievementTypeEnum::COIN->value)
                    ->sum('count');

                $coinRequestedPending = $this->coinRequests()
                    ->where('type', CoinRequestTypeEnum::SELL->value)
                    ->where('status_id', Status::nameScope(StatusEnum::PENDING->value)->first()->id)
                    ->sum('count');

                return $count - $coinRequestedPending;
            }
        );
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hasAdminRole = $this->roles?->where('name', 'admin')->first();

                return $hasAdminRole ? true : false;
            }
        );
    }

    protected function username9(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->username, 0, 9).'...'
        );
    }
}
