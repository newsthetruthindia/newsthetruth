<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // User/Staff Permissions
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            // Role Permissions
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            // Post Permissions
            'view_any_post', 'view_post', 'create_post', 'update_post', 'delete_post',
            // Category Permissions
            'view_any_category', 'view_category', 'create_category', 'update_category', 'delete_category',
            // Tag Permissions
            'view_any_tag', 'view_tag', 'create_tag', 'update_tag', 'delete_tag',
            // Media Permissions
            'view_any_media', 'view_media', 'create_media', 'update_media', 'delete_media',
            // Video Permissions
            'view_any_video', 'view_video', 'create_video', 'update_video', 'delete_video',
            // Citizen Journalism Permissions
            'view_any_citizen_journalism', 'view_citizen_journalism', 'create_citizen_journalism', 'update_citizen_journalism', 'delete_citizen_journalism',
            // Subscriber Permissions
            'view_any_subscriber', 'view_subscriber', 'create_subscriber', 'update_subscriber', 'delete_subscriber',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create Roles and Assign Permissions
        
        // Admin: All permissions
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        // Editor: Everything except user/role management and deleting posts
        $editorRole = Role::findOrCreate('editor');
        $editorRole->givePermissionTo([
            'view_any_post', 'view_post', 'create_post', 'update_post',
            'view_any_category', 'view_category', 'create_category', 'update_category', 'delete_category',
            'view_any_tag', 'view_tag', 'create_tag', 'update_tag', 'delete_tag',
            'view_any_media', 'view_media', 'create_media', 'update_media', 'delete_media',
            'view_any_video', 'view_video', 'create_video', 'update_video', 'delete_video',
            'view_any_citizen_journalism', 'view_citizen_journalism', 'create_citizen_journalism', 'update_citizen_journalism', 'delete_citizen_journalism',
            'view_any_subscriber', 'view_subscriber', 'create_subscriber', 'update_subscriber', 'delete_subscriber',
        ]);

        // Assign 'admin' role to existing admin users
        $admins = User::where('type', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->assignRole($adminRole);
        }

        // Assign 'editor' role to existing employee users (if any)
        $employees = User::where('type', 'employee')->get();
        foreach ($employees as $employee) {
            $employee->assignRole($editorRole);
        }
    }
}
