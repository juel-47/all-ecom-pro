<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class FixPermissions extends Command
{
    protected $signature = 'fix:permissions';
    protected $description = 'Fix permissions and role assignments';

    public function handle()
    {
        $this->info('Creating permissions...');
        
        // Create permissions
        $permissions = [
            'create orders', 'edit orders', 'delete orders', 'view orders',
            'create products', 'edit products', 'delete products', 'view products',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
            $this->info("Created permission: {$perm}");
        }

        $this->info('Creating roles...');
        
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        $accountants = Role::firstOrCreate(['name' => 'Accountants', 'guard_name' => 'web']);
        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        $this->info('Assigning permissions to roles...');
        
        // Assign permissions to roles
        $superAdmin->syncPermissions(Permission::all());
        $this->info('SuperAdmin gets all permissions');

        $accountants->syncPermissions([
            'create orders', 'edit orders', 'delete orders', 'view orders'
        ]);
        $this->info('Accountants gets order permissions');

        $customer->syncPermissions([
            'view orders', 'view products', 'create orders'
        ]);
        $this->info('Customer gets view orders, view products, create orders');

        $this->info('Assigning roles to users...');
        
        // Assign roles to users
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->syncRoles([$superAdmin]);
            $this->info('Admin assigned SuperAdmin role');
        }

        $hasan = User::where('email', 'hasan@example.com')->first();
        if ($hasan) {
            $hasan->syncRoles([$accountants]);
            $this->info('Hasan assigned Accountants role');
        }

        $user1 = User::where('email', 'user1@example.com')->first();
        if ($user1) {
            $user1->syncRoles([$customer]);
            $this->info('User1 assigned Customer role');
        }

        $this->info('Permissions and roles fixed successfully!');
        
        return 0;
    }
}
