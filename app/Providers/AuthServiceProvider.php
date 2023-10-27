<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Club;
use App\Models\Competition;
use App\Models\Role;
use App\Models\User;
use App\Policies\ClubPolicy;
use App\Policies\CompetitionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Club::class => ClubPolicy::class,
        Competition::class => CompetitionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
