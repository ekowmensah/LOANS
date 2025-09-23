<?php
/**
 * Script to assign all permissions to admin user
 * Based on the successful pattern from add_group_menu.php
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\User\Entities\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Find admin user
$admin = User::where('email', 'admin@localhost.com')->first();

if (!$admin) {
    echo "Admin user not found! Creating one...\n";
    
    $admin = new User();
    $admin->name = 'Admin';
    $admin->username = 'admin';
    $admin->email = 'admin@localhost.com';
    $admin->password = bcrypt('password');
    $admin->first_name = 'Admin';
    $admin->last_name = 'User';
    $admin->branch_id = 1;
    $admin->email_verified_at = now();
    $admin->save();
    
    echo "Admin user created (ID: {$admin->id})\n";
} else {
    echo "Found admin user (ID: {$admin->id})\n";
}

// Create or get super admin role
$superAdminRole = Role::firstOrCreate(['name' => 'admin']);
echo "Super admin role ready (ID: {$superAdminRole->id})\n";

// Get all permissions
$permissions = Permission::all();
echo "Found {$permissions->count()} permissions\n";

// Assign all permissions to super admin role
$superAdminRole->syncPermissions($permissions);
echo "All permissions assigned to super admin role\n";

// Assign super admin role to admin user
$admin->assignRole($superAdminRole);
echo "Super admin role assigned to admin user\n";

// Also give direct permissions for good measure
$admin->givePermissionTo($permissions);
echo "All permissions also assigned directly to admin user\n";

echo "Admin user now has full access to all features!\n";
echo "Login with: admin@localhost.com / password\n";
