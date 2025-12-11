<?php
/**
 * Web-based Field Agent Permissions Seeder
 * 
 * IMPORTANT: Delete this file after running for security!
 * 
 * Usage:
 * 1. Upload this file to your Laravel root directory (same level as artisan)
 * 2. Access via browser: https://yourdomain.com/run_field_agent_permissions_seeder_web.php
 * 3. DELETE this file immediately after successful run
 */

// Security check - uncomment and set a secret key
// $secret_key = 'your-secret-key-here'; // Change this!
// if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
//     die('Unauthorized access');
// }

echo "<h1>Field Agent Permissions Seeder</h1>";
echo "<p>Starting seeder execution...</p>";
echo "<hr>";

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

try {
    echo "<h2>Creating Permissions...</h2>";
    
    // Create permissions
    $permissions = [
        // Field Agent Management
        ['name' => 'field_agent.agents.index', 'display_name' => 'View Field Agents', 'guard_name' => 'web'],
        ['name' => 'field_agent.agents.create', 'display_name' => 'Create Field Agent', 'guard_name' => 'web'],
        ['name' => 'field_agent.agents.edit', 'display_name' => 'Edit Field Agent', 'guard_name' => 'web'],
        ['name' => 'field_agent.agents.destroy', 'display_name' => 'Delete Field Agent', 'guard_name' => 'web'],
        ['name' => 'field_agent.agents.view', 'display_name' => 'View Field Agent Details', 'guard_name' => 'web'],

        // Collection Management
        ['name' => 'field_agent.collections.index', 'display_name' => 'View Collections', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.create', 'display_name' => 'Record Collection', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.view', 'display_name' => 'View Collection Details', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.verify', 'display_name' => 'Verify Collection', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.post', 'display_name' => 'Post Collection', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.reject', 'display_name' => 'Reject Collection', 'guard_name' => 'web'],
        ['name' => 'field_agent.collections.view_own', 'display_name' => 'View Own Collections', 'guard_name' => 'web'],

        // Daily Report Management
        ['name' => 'field_agent.reports.index', 'display_name' => 'View Daily Reports', 'guard_name' => 'web'],
        ['name' => 'field_agent.reports.create', 'display_name' => 'Create Daily Report', 'guard_name' => 'web'],
        ['name' => 'field_agent.reports.view', 'display_name' => 'View Report Details', 'guard_name' => 'web'],
        ['name' => 'field_agent.reports.approve', 'display_name' => 'Approve Daily Report', 'guard_name' => 'web'],
        ['name' => 'field_agent.reports.reject', 'display_name' => 'Reject Daily Report', 'guard_name' => 'web'],
        ['name' => 'field_agent.reports.view_own', 'display_name' => 'View Own Reports', 'guard_name' => 'web'],

        // Dashboard & Analytics
        ['name' => 'field_agent.dashboard.view', 'display_name' => 'View Field Agent Dashboard', 'guard_name' => 'web'],
        ['name' => 'field_agent.analytics.view', 'display_name' => 'View Analytics', 'guard_name' => 'web'],

        // Client Assignment Management
        ['name' => 'field_agent.assignments.index', 'display_name' => 'View Client Assignments', 'guard_name' => 'web'],
        ['name' => 'field_agent.assignments.create', 'display_name' => 'Create Client Assignment', 'guard_name' => 'web'],
        ['name' => 'field_agent.assignments.edit', 'display_name' => 'Edit Client Assignment', 'guard_name' => 'web'],
        ['name' => 'field_agent.assignments.destroy', 'display_name' => 'Delete Client Assignment', 'guard_name' => 'web'],
    ];

    $created = 0;
    foreach ($permissions as $permission) {
        $perm = Permission::firstOrCreate(
            ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
            ['display_name' => $permission['display_name']]
        );
        echo "✅ {$permission['name']}<br>";
        $created++;
    }

    echo "<p><strong>✅ {$created} permissions created/verified successfully!</strong></p>";

    // Assign all permissions to admin role if it exists
    echo "<h2>Assigning Permissions to Admin Role...</h2>";
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        $permissionNames = array_column($permissions, 'name');
        $adminRole->givePermissionTo($permissionNames);
        echo "<p>✅ Permissions assigned to admin role!</p>";
    } else {
        echo "<p>⚠️ Admin role not found. Skipping admin assignment.</p>";
    }

    // Create Field Agent Manager role
    echo "<h2>Creating Field Agent Manager Role...</h2>";
    $managerRole = Role::firstOrCreate(
        ['name' => 'field_agent_manager', 'guard_name' => 'web'],
        ['display_name' => 'Field Agent Manager']
    );

    $managerPermissions = [
        'field_agent.agents.index',
        'field_agent.agents.create',
        'field_agent.agents.edit',
        'field_agent.agents.view',
        'field_agent.collections.index',
        'field_agent.collections.view',
        'field_agent.collections.verify',
        'field_agent.collections.post',
        'field_agent.collections.reject',
        'field_agent.reports.index',
        'field_agent.reports.view',
        'field_agent.reports.approve',
        'field_agent.reports.reject',
        'field_agent.analytics.view',
        'field_agent.assignments.index',
        'field_agent.assignments.create',
        'field_agent.assignments.edit',
        'field_agent.assignments.destroy',
    ];

    $managerRole->syncPermissions($managerPermissions);
    echo "<p>✅ Field Agent Manager role created with " . count($managerPermissions) . " permissions!</p>";

    // Create Field Agent role
    echo "<h2>Creating Field Agent Role...</h2>";
    $agentRole = Role::firstOrCreate(
        ['name' => 'field_agent', 'guard_name' => 'web'],
        ['display_name' => 'Field Agent']
    );

    $agentPermissions = [
        'field_agent.collections.create',
        'field_agent.collections.view_own',
        'field_agent.reports.create',
        'field_agent.reports.view_own',
    ];

    $agentRole->syncPermissions($agentPermissions);
    echo "<p>✅ Field Agent role created with " . count($agentPermissions) . " permissions!</p>";

    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 SUCCESS!</h2>";
    echo "<p><strong>All Field Agent permissions and roles created successfully!</strong></p>";
    
    echo "<hr>";
    echo "<h3 style='color: red;'>⚠️ IMPORTANT SECURITY NOTICE</h3>";
    echo "<p style='color: red; font-weight: bold;'>DELETE THIS FILE IMMEDIATELY FOR SECURITY!</p>";
    echo "<p>File to delete: run_field_agent_permissions_seeder_web.php</p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ ERROR</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
