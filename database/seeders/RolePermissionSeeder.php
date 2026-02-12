<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
            'view qualification requests',
            'review qualification requests',
            'approve qualification requests',
            'reject qualification requests',
            'view committees',
            'create committees',
            'edit committees',
            'delete committees',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage settings',
            'manage faqs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $committeeMember = Role::create(['name' => 'Committee Member']);

        // Assign all permissions to Admin
        $admin->givePermissionTo(Permission::all());

        // Assign permissions to Committee Member (عضو اللجنة)
        // عضو اللجنة: الشركات وطلبات التأهيل فقط
        $committeeMember->givePermissionTo([
            'view companies',
            'view qualification requests',
            'review qualification requests',
            'approve qualification requests',
            'reject qualification requests',
        ]);

        // Create Chairman role (رئيس اللجنة)
        $chairman = Role::create(['name' => 'Chairman']);
        // رئيس اللجنة: الشركات وطلبات التأهيل فقط
        $chairman->givePermissionTo([
            'view companies',
            'view qualification requests',
            'review qualification requests',
            'approve qualification requests',
            'reject qualification requests',
        ]);
    }
}
