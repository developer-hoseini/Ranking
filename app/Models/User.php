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

    public function userScoreAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::SCORE->value);
    }

    public function userJoinGameAchievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievementable')->where('type', AchievementTypeEnum::JOIN->value);
    }

    public function userCoinAchievements(): MorphMany
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
        return $this->morphMany(GameResult::class, 'playerable');
    }

    public function userInvites(): MorphMany
    {
        return $this->morphMany(Invite::class, 'inviteable');
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

    public function scopeHasAgentRolesScope(Builder $builder): Builder
    {
        return $builder->whereHas('roles', fn ($q) => $q->where('name', 'like', 'agent%'));
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avatar = $this->getFirstMediaUrl('avatar');

                if ($avatar) {
                    return $avatar ?? '';
                }

                return asset('assets/images/default-profile.png');
            }
        );
    }

    protected function avatarName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->profile?->avatar_name ?? $this->username ?? $this->name ?? "user-doesn't-have-name";
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

                return (bool) $hasAdminRole;
            }
        );
    }

    private function checkHasAgent(string $type): bool
    {
        $hasAgentRole = $this->roles?->where('name', "agent-$type")->first();

        return (bool) $hasAgentRole;
    }

    protected function isAgent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hasAgentRole = $this->roles?->whereIn('name', ['agent-a', 'agent-b', 'agent-c'])->first();

                return (bool) $hasAgentRole;
            }
        );
    }

    protected function isAgentA(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkHasAgent('a')
        );
    }

    protected function isAgentB(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkHasAgent('b')
        );
    }

    protected function isAgentC(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkHasAgent('c')
        );
    }

    protected function username9(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->username, 0, 9).'...'
        );
    }
}
