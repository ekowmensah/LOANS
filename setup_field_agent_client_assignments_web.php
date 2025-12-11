<?php
/**
 * Web-based Field Agent Client Assignment Setup
 * This script sets up permissions and menus for the client assignment feature
 * 
 * IMPORTANT: Delete this file after running for security!
 * 
 * Usage:
 * 1. Upload this file to your Laravel root directory (same level as artisan)
 * 2. Access via browser: https://yourdomain.com/setup_field_agent_client_assignments_web.php
 * 3. DELETE this file immediately after successful run
 */

// Security check - uncomment and set a secret key
// $secret_key = 'your-secret-key-here'; // Change this!
// if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
//     die('Unauthorized access');
// }

echo "<!DOCTYPE html><html><head><title>Field Agent Client Assignment Setup</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:900px;margin:50px auto;padding:20px;}";
echo ".success{color:green;}.error{color:red;}.warning{color:orange;}";
echo "h1{color:#333;}h2{color:#666;border-bottom:2px solid #ddd;padding-bottom:10px;}";
echo "pre{background:#f5f5f5;padding:10px;border-radius:5px;overflow-x:auto;}";
echo ".alert{background:#fff3cd;border:1px solid #ffc107;padding:15px;border-radius:5px;margin:20px 0;}";
echo "</style></head><body>";

echo "<h1>🚀 Field Agent Client Assignment Setup</h1>";
echo "<p>Setting up permissions and menus for client-field agent assignments...</p>";
echo "<hr>";

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$errors = [];
$success = [];

try {
    // ========================================================================
    // STEP 1: CREATE/UPDATE PERMISSIONS
    // ========================================================================
    echo "<h2>Step 1: Setting Up Permissions</h2>";
    
    $permissions = [
        ['name' => 'field_agent.assignments.index', 'display_name' => 'View Client Assignments'],
        ['name' => 'field_agent.assignments.create', 'display_name' => 'Assign Clients to Field Agents'],
    ];

    $permCount = 0;
    foreach ($permissions as $permission) {
        $perm = Permission::firstOrCreate(
            ['name' => $permission['name'], 'guard_name' => 'web'],
            ['display_name' => $permission['display_name']]
        );
        echo "<p class='success'>✅ {$permission['name']}</p>";
        $permCount++;
    }

    $success[] = "{$permCount} permissions created/verified";

    // Assign to admin role
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        $adminRole->givePermissionTo(array_column($permissions, 'name'));
        echo "<p class='success'>✅ Permissions assigned to admin role</p>";
        $success[] = "Admin role updated";
    } else {
        echo "<p class='warning'>⚠️ Admin role not found</p>";
    }

    // Assign to field_agent_manager role
    $managerRole = Role::where('name', 'field_agent_manager')->first();
    if ($managerRole) {
        $managerRole->givePermissionTo(array_column($permissions, 'name'));
        echo "<p class='success'>✅ Permissions assigned to field_agent_manager role</p>";
        $success[] = "Field Agent Manager role updated";
    } else {
        echo "<p class='warning'>⚠️ Field Agent Manager role not found</p>";
    }

    // ========================================================================
    // STEP 2: CREATE/UPDATE MENUS
    // ========================================================================
    echo "<h2>Step 2: Setting Up Menu Items</h2>";

    // Check if menus table exists
    if (!DB::getSchemaBuilder()->hasTable('menus')) {
        throw new Exception("'menus' table does not exist!");
    }

    // Find or create Field Agents parent menu
    $parentMenu = DB::table('menus')->where('name', 'Field Agents')->first();

    if (!$parentMenu) {
        $parentMenuId = DB::table('menus')->insertGetId([
            'name' => 'Field Agents',
            'title' => 'Field Agents',
            'parent_id' => null,
            'url' => '/field-agent/agent',
            'icon' => 'fas fa-walking',
            'permissions' => 'field_agent.agents.index',
            'menu_order' => 50,
            'is_parent' => 1,
            'module' => 'FieldAgent',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "<p class='success'>✅ Created Field Agents parent menu (ID: {$parentMenuId})</p>";
    } else {
        $parentMenuId = $parentMenu->id;
        echo "<p class='success'>✅ Field Agents parent menu exists (ID: {$parentMenuId})</p>";
    }

    // Remove old assignment menu items if they exist
    $deleted = DB::table('menus')
        ->where('parent_id', $parentMenuId)
        ->whereIn('name', ['Client Assignments', 'Assign Client'])
        ->delete();
    
    if ($deleted > 0) {
        echo "<p class='warning'>🗑️ Removed {$deleted} old assignment menu items</p>";
    }

    // Add new Client Assignments menu item
    $existingMenu = DB::table('menus')
        ->where('parent_id', $parentMenuId)
        ->where('name', 'Assign Clients')
        ->first();

    if (!$existingMenu) {
        DB::table('menus')->insert([
            'name' => 'Assign Clients',
            'title' => 'Assign Clients to Field Agents',
            'parent_id' => $parentMenuId,
            'url' => '/field-agent/client-assignments',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.assignments.index',
            'menu_order' => 8,
            'is_parent' => 0,
            'module' => 'FieldAgent',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "<p class='success'>✅ Created 'Assign Clients' menu item</p>";
        $success[] = "Menu item created";
    } else {
        echo "<p class='success'>✅ 'Assign Clients' menu item already exists</p>";
        $success[] = "Menu item verified";
    }

    // ========================================================================
    // SUMMARY
    // ========================================================================
    echo "<hr>";
    echo "<h2 class='success'>🎉 SETUP COMPLETE!</h2>";
    
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    foreach ($success as $msg) {
        echo "<li>✅ {$msg}</li>";
    }
    echo "</ul>";

    echo "<h3>What was set up:</h3>";
    echo "<ul>";
    echo "<li><strong>Permissions:</strong></li>";
    echo "<ul>";
    foreach ($permissions as $perm) {
        echo "<li>{$perm['name']} - {$perm['display_name']}</li>";
    }
    echo "</ul>";
    echo "<li><strong>Menu Item:</strong> Assign Clients (under Field Agents menu)</li>";
    echo "</ul>";

    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Run the SQL migration to add field_agent_id column to clients table</li>";
    echo "<li>Clear cache (if possible): <code>php artisan cache:clear</code></li>";
    echo "<li>Refresh your browser</li>";
    echo "<li>Navigate to: <strong>Field Agents → Assign Clients</strong></li>";
    echo "<li><strong class='error'>DELETE THIS FILE IMMEDIATELY!</strong></li>";
    echo "</ol>";

    echo "<div class='alert'>";
    echo "<h3 class='error'>⚠️ IMPORTANT SECURITY NOTICE</h3>";
    echo "<p><strong class='error'>DELETE THIS FILE NOW FOR SECURITY!</strong></p>";
    echo "<p>File to delete: <code>setup_field_agent_client_assignments_web.php</code></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h2 class='error'>❌ ERROR</h2>";
    echo "<p class='error'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";
?>
