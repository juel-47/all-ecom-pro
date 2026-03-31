<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //     $permissions = [
        //     // Orders
        //     'create orders', 'edit orders', 'delete orders', 'view orders',

        //     // Products
        //     'create products', 'edit products', 'delete products', 'view products',

        //     // Invoices
        //     'create invoices', 'edit invoices', 'delete invoices', 'view invoices',

        //     // Payments
        //     'create payments', 'edit payments', 'delete payments', 'view payments',

        //     // Expenses
        //     'create expenses', 'edit expenses', 'delete expenses', 'view expenses',

        //     // Reports
        //     'view financial reports', 'export financial reports',

        //     // User & Role Management
        //     'create users', 'edit users', 'delete users', 'view users',
        //     'create roles', 'edit roles', 'delete roles', 'view roles',
        //     ];

        //     foreach ($permissions as $perm) {
        //         Permission::firstOrCreate([
        //             'name' => $perm,
        //             'guard_name' => 'web',
        //         ]);
        //     }

        // // Roles
        // $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        // $accountants = Role::firstOrCreate(['name' => 'Accountants', 'guard_name' => 'web']);
        // $customer   = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        // $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);

        // // Assign permissions   
        // $superAdmin->syncPermissions(Permission::all());

        // $accountants->syncPermissions([
        //     'view orders', 'edit orders', 'view products',  'create invoices', 'edit invoices', 'view invoices',
        //     'create payments', 'edit payments', 'view payments','create expenses', 'edit expenses', 'view expenses',  
        //     'view financial reports', 'export financial reports',
        // ]);

        // $customer->syncPermissions([
        //     'view orders', 'view products', 'create orders',
        // ]);

        // $manager->syncPermissions([
        //     'edit orders', 'view orders','create products', 'edit products', 'view products',
        //     'view invoices','view payments','view expenses','view financial reports',
        // ]);

        // // Assign roles to users
        //         User::where('email', 'admin@example.com')->first()?->syncRoles([$superAdmin]);
        //         User::where('email', 'hasan@example.com')->first()?->syncRoles([$accountants]);
        //         User::where('email', 'manager@example.com')->first()?->syncRoles([$manager]);
        //         $allUsers = User::whereNotIn('email', ['admin@example.com','hasan@example.com','manager@example.com'])->get();
        //         foreach ($allUsers as $user) {
        //         $user->syncRoles([$customer]);
        //     }
        $permissions = [
            // Orders
            'create orders',
            'edit orders',
            'delete orders',
            'view orders',

            // Products
            'create products',
            'edit products',
            'delete products',
            'view products',

            // Invoices
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view invoices',

            // Payments
            'create payments',
            'edit payments',
            'delete payments',
            'view payments',

            // Expenses
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view expenses',

            // Reports
            'view financial reports',
            'export financial reports',

            // User & Role Management
            'create users',
            'edit users',
            'delete users',
            'view users',
            'create roles',
            'edit roles',
            'delete roles',
            'view roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        // Roles
        $superAdmin  = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        $accountants = Role::firstOrCreate(['name' => 'Accountants', 'guard_name' => 'web']);
        $customer    = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'sanctum']);
        $manager     = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);

        // Assign permissions
        $superAdmin->syncPermissions(Permission::all());

        $accountants->syncPermissions([
            'view orders',
            'edit orders',
            'view products',
            'create invoices',
            'edit invoices',
            'view invoices',
            'create payments',
            'edit payments',
            'view payments',
            'create expenses',
            'edit expenses',
            'view expenses',
            'view financial reports',
            'export financial reports',
        ]);

        $customer->syncPermissions([
            'view orders',
            'view products',
            'create orders',
        ]);

        $manager->syncPermissions([
            'edit orders',
            'view orders',
            'create products',
            'edit products',
            'view products',
            'view invoices',
            'view payments',
            'view expenses',
            'view financial reports',
        ]);

        // ✅ Create users if not exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // change if needed
            ]
        );
        $admin->syncRoles([$superAdmin]);

        $hasan = User::firstOrCreate(
            ['email' => 'hasan@example.com'],
            [
                'name' => 'Hasan Accountant',
                'password' => bcrypt('password'),
            ]
        );
        $hasan->syncRoles([$accountants]);

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => bcrypt('password'),
            ]
        );
        $managerUser->syncRoles([$manager]);

        // All other users get customer role
        $allUsers = User::whereNotIn('email', [
            'admin@example.com',
            'hasan@example.com',
            'manager@example.com'
        ])->get();

        foreach ($allUsers as $user) {
            $user->syncRoles([$customer]);
        }
    }
}
