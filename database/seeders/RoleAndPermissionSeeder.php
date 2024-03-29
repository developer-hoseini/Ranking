<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = collect([
            ['name' => 'admin'],
            ['name' => 'client'],
            ['name' => 'agent-a'],
            ['name' => 'agent-b'],
            ['name' => 'agent-c'],
        ]);
        /*
                $permissions = collect([
                    ['name' => 'show', 'label' => 'نمایش'], ['name' => 'create'], ['name' => 'edit'], ['name' => 'delete'],
                ]);

                $modelsOnlyShow = collect([
                    ['name' => 'panel admin'],
                    ['name' => 'dashboard'],
                ]);

                $modelsHavePermissionAdmin = collect([
                ]);

                $customPermissions = collect([
                ]);

                $models = collect([
                    ['name' => 'game'],
                    ['name' => 'user'],
                    ['name' => 'role'],
                    ['name' => 'permission'],
                    ['name' => 'club'],
                    ['name' => 'competition'],
                    ['name' => 'cup'],
                    ['name' => 'game-result'],
                    ['name' => 'invite'],
                    ['name' => 'team'],
                ]);
        */

        $adminReports = [];

        $userReports = [];

        try {
            $roles->each(function ($role) {
                Role::updateOrCreate([
                    'name' => $role['name'],
                    'guard_name' => 'web',
                ]);
            });
        } catch (\Exception $error) {
            throw $error;
        }

        try {

            /*
            $modelsOnlyShow->each(function ($item) {
                Permission::updateOrCreate([
                    'name' => 'show '.$item['name'],
                    'guard_name' => 'web',
                ]);
            });

            $customPermissions->each(function ($item) {
                Permission::updateOrCreate([
                    'name' => $item['name'],
                    'guard_name' => 'web',
                ]);
            });

            $models->each(function ($model) use ($permissions, $modelsHavePermissionAdmin) {
                $permissions->each(function ($permission) use ($model) {
                    Permission::updateOrCreate([
                        'name' => $permission['name'].' '.$model['name'],
                        'guard_name' => 'web',
                    ]);
                });
                if (collect($modelsHavePermissionAdmin)->contains('name', $model['name'])) {
                    Permission::updateOrCreate([
                        'name' => 'admin '.$model['name'],
                        'guard_name' => 'web',
                    ]);
                }
            });

            collect($adminReports)->each(function ($report) {
                Permission::updateOrCreate([
                    'name' => $report['name'],
                    'guard_name' => 'web',
                ]);
            });

            collect($userReports)->each(function ($report) {
                Permission::updateOrCreate([
                    'name' => $report['name'],
                    'guard_name' => 'web',
                ]);
            });
        */

            \Artisan::call('permissions:sync');

            $adminRole = Role::where('name', 'admin')->first();

            $user = User::where('email', 'admin@admin.com')->firstOr(function () {
                return User::create([
                    'name' => 'admin',
                    'email' => 'admin@admin.com',
                    'username' => 'admin',
                    'password' => bcrypt('admin'),
                    'email_verified_at' => now(),
                ]);
            });

            if (app()->environment('local')) {

                $clientRole = Role::where('name', 'client')->first();
                $fakeUsers = [
                    [
                        'name' => 'userfake1',
                        'email' => 'userfake1@user.com',
                        'username' => 'userfake1',
                        'password' => bcrypt('userfake1'),
                        'email_verified_at' => now(),

                    ],
                    [
                        'name' => 'userfake2',
                        'email' => 'userfake2@user.com',
                        'username' => 'userfake2',
                        'password' => bcrypt('userfake2'),
                        'email_verified_at' => now(),

                    ],
                    [
                        'name' => 'userfake3',
                        'email' => 'userfake3@user.com',
                        'username' => 'userfake3',
                        'password' => bcrypt('userfake3'),
                        'email_verified_at' => now(),

                    ],
                ];

                foreach ($fakeUsers as $fakeUser) {
                    $createdUser = User::updateOrCreate($fakeUser);
                    $createdUser->roles()->sync($clientRole);
                    $team = Team::updateOrCreate([
                        'name' => "Team {$fakeUser['name']}",
                    ]);

                    if ($createdUser->wasRecentlyCreated) {
                        $team->users()->attach($createdUser);
                    }
                }
            }

            $user->roles()->sync($adminRole);
            $allPermissions = Permission::where('guard_name', 'web')->get();
            $adminRole->givePermissionTo($allPermissions);

            \Artisan::call('cache:clear');
            //            }
        } catch (\Throwable $th) {
            // throw $th;
        }

    }
}
