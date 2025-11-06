<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Auto create roles saat pertama kali
        if (!app()->runningInConsole()) {
            $roles = ["student", "instructor", "admin"];
            foreach ($roles as $role) {
                if (!Role::where("name", $role)->exists()) {
                    Role::create(["name" => $role]);
                }
            }
        }
    }

    private function createRolesAndPermissions(): void
    {
        // Create permissions
        Permission::create(["name" => "create-post"]);
        Permission::create(["name" => "update-post"]);
        Permission::create(["name" => "delete-post"]);

        // Create roles
        $admin = Role::create(["name" => "admin"]);
        $editor = Role::create(["name" => "editor"]);
        $user = Role::create(["name" => "user"]);

        // Assign permissions to roles
        $admin->givePermissionTo(["create-post", "update-post", "delete-post"]);
        $editor->givePermissionTo(["create-post", "update-post"]);
        $user->givePermissionTo(["create-post"]);
    }
}
