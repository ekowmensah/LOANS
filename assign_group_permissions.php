<?php
/**
 * Script to assign group permissions to admin role
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== ASSIGNING GROUP PERMISSIONS ===\n\n";

// Group permissions to assign
$groupPermissions = [
    'client.groups.index',
    'client.groups.create', 
    'client.groups.edit',
    'client.groups.destroy',
    'client.groups.manage_members'
];

// Find admin role (or create if doesn't exist)
$adminRole = Role::firstOrCreate(['name' => 'admin']);
echo "Admin role: {$adminRole->name} (ID: {$adminRole->id})\n\n";

// Create and assign permissions
foreach ($groupPermissions as $permissionName) {
    // Create permission if it doesn't exist
    $permission = Permission::firstOrCreate(['name' => $permissionName]);
    
    // Assign to admin role
    if (!$adminRole->hasPermissionTo($permission)) {
        $adminRole->givePermissionTo($permission);
        echo "✅ Assigned permission: {$permissionName}\n";
    } else {
        echo "ℹ️  Already has permission: {$permissionName}\n";
    }
}

echo "\n=== PERMISSIONS ASSIGNMENT COMPLETE ===\n";
echo "Please log in to see the Groups menu.\n";
