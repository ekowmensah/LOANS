<?php
/**
 * Web-based Field Agent Menu Seeder
 * 
 * IMPORTANT: Delete this file after running for security!
 * 
 * Usage:
 * 1. Upload this file to your Laravel root directory (same level as artisan)
 * 2. Access via browser: https://yourdomain.com/run_field_agent_menus_seeder_web.php
 * 3. DELETE this file immediately after successful run
 */

// Security check - uncomment and set a secret key
// $secret_key = 'your-secret-key-here'; // Change this!
// if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
//     die('Unauthorized access');
// }

echo "<h1>Field Agent Menu Seeder</h1>";
echo "<p>Starting menu setup...</p>";
echo "<hr>";

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Check if menus table exists
    if (!DB::getSchemaBuilder()->hasTable('menus')) {
        throw new Exception("'menus' table does not exist!");
    }

    echo "<h2>Checking for Existing Menu...</h2>";
    
    // Check if Field Agent menu already exists
    $existingMenu = DB::table('menus')->where('name', 'Field Agents')->first();

    if ($existingMenu) {
        echo "<p>ℹ️ Field Agent menu already exists. Updating...</p>";
        $parentMenuId = $existingMenu->id;
        
        // Delete old child menus
        $deleted = DB::table('menus')->where('parent_id', $parentMenuId)->delete();
        echo "<p>🗑️ Deleted {$deleted} old child menus</p>";
    } else {
        echo "<h2>Creating Parent Menu...</h2>";
        
        // Insert parent menu
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
        
        echo "<p>✅ Parent menu created (ID: {$parentMenuId})</p>";
    }

    // Insert child menus
    echo "<h2>Creating Child Menus...</h2>";

    $childMenus = [
        [
            'name' => 'All Field Agents',
            'title' => 'All Field Agents',
            'url' => '/field-agent/agent',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.agents.index',
            'order' => 1,
        ],
        [
            'name' => 'Add Field Agent',
            'title' => 'Add Field Agent',
            'url' => '/field-agent/agent/create',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.agents.create',
            'order' => 2,
        ],
        [
            'name' => 'Field Collections',
            'title' => 'Field Collections',
            'url' => '/field-agent/collection',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.collections.index',
            'order' => 3,
        ],
        [
            'name' => 'Record Collection',
            'title' => 'Record Collection',
            'url' => '/field-agent/collection/create',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.collections.create',
            'order' => 4,
        ],
        [
            'name' => 'Verify Collections',
            'title' => 'Verify Collections',
            'url' => '/field-agent/collection/verify',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.collections.verify',
            'order' => 5,
        ],
        [
            'name' => 'Daily Reports',
            'title' => 'Daily Reports',
            'url' => '/field-agent/daily-report',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.reports.index',
            'order' => 6,
        ],
        [
            'name' => 'Submit Daily Report',
            'title' => 'Submit Daily Report',
            'url' => '/field-agent/daily-report/create',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.reports.create',
            'order' => 7,
        ],
        [
            'name' => 'Client Assignments',
            'title' => 'Client Assignments',
            'url' => '/field-agent/assignments',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.assignments.index',
            'order' => 8,
        ],
        [
            'name' => 'Assign Client',
            'title' => 'Assign Client',
            'url' => '/field-agent/assignments/create',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.assignments.create',
            'order' => 9,
        ],
    ];

    $created = 0;
    foreach ($childMenus as $menu) {
        DB::table('menus')->insert([
            'name' => $menu['name'],
            'title' => $menu['title'],
            'parent_id' => $parentMenuId,
            'url' => $menu['url'],
            'icon' => $menu['icon'],
            'permissions' => $menu['permissions'],
            'menu_order' => $menu['order'],
            'is_parent' => 0,
            'module' => 'FieldAgent',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $created++;
        echo "✅ Created: {$menu['name']}<br>";
    }

    echo "<p><strong>✅ {$created} child menus created!</strong></p>";

    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 MENU SETUP COMPLETE!</h2>";
    
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li>✅ 1 parent menu: Field Agents</li>";
    echo "<li>✅ {$created} child menus created</li>";
    echo "</ul>";

    echo "<h3>Menu Structure:</h3>";
    echo "<ul>";
    echo "<li>📁 Field Agents</li>";
    echo "<ul>";
    foreach ($childMenus as $menu) {
        echo "<li>└─ {$menu['name']}</li>";
    }
    echo "</ul>";
    echo "</ul>";

    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Clear cache (if possible)</li>";
    echo "<li>Refresh your browser</li>";
    echo "<li>Look for 'Field Agents' in the navigation menu</li>";
    echo "</ol>";

    echo "<hr>";
    echo "<h3 style='color: red;'>⚠️ IMPORTANT SECURITY NOTICE</h3>";
    echo "<p style='color: red; font-weight: bold;'>DELETE THIS FILE IMMEDIATELY FOR SECURITY!</p>";
    echo "<p>File to delete: run_field_agent_menus_seeder_web.php</p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ ERROR</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
