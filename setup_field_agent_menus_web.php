<?php
/**
 * Web-based Field Agent Menu Setup
 * Upload this file to your server root and access via browser
 * URL: https://yourdomain.com/setup_field_agent_menus_web.php
 * 
 * IMPORTANT: Delete this file after running!
 */

// Prevent direct access without confirmation
$confirm = isset($_GET['confirm']) && $_GET['confirm'] === 'yes';

if (!$confirm) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Field Agent Menu Setup</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .button { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
            .button:hover { background: #0056b3; }
            .danger { background: #dc3545; }
            .danger:hover { background: #c82333; }
            h1 { color: #333; }
            ul { line-height: 1.8; }
        </style>
    </head>
    <body>
        <h1>🚀 Field Agent Menu Setup</h1>
        
        <div class="warning">
            <strong>⚠️ Warning:</strong> This will add Field Agent menus to your database.
        </div>
        
        <h2>What this will do:</h2>
        <ul>
            <li>✅ Create "Field Agents" parent menu</li>
            <li>✅ Create 7 child menus (All Agents, Add Agent, Collections, etc.)</li>
            <li>✅ Set proper permissions and icons</li>
            <li>✅ Update existing menus if already present</li>
        </ul>
        
        <h2>Before proceeding:</h2>
        <ul>
            <li>Make sure you have database backup</li>
            <li>Ensure Field Agent permissions are already seeded</li>
            <li>You must be logged in as admin</li>
        </ul>
        
        <a href="?confirm=yes" class="button">✅ Yes, Setup Menus</a>
        <a href="/" class="button danger">❌ Cancel</a>
        
        <p style="color: #666; margin-top: 30px;">
            <strong>Note:</strong> Delete this file after running for security!
        </p>
    </body>
    </html>
    <?php
    exit;
}

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Field Agent Menu Setup - Results</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .log { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; font-size: 14px; }
        h1 { color: #333; }
        .button { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .button:hover { background: #0056b3; }
        .danger { background: #dc3545; }
        .danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <h1>🚀 Field Agent Menu Setup</h1>
    
    <div class="log">
<?php

try {
    echo "Starting Field Agent Menu Setup...\n\n";
    
    // Check if menus table exists
    if (!DB::getSchemaBuilder()->hasTable('menus')) {
        throw new Exception("Error: 'menus' table does not exist!");
    }
    echo "✅ Menus table found\n\n";
    
    // Check if Field Agent menu already exists
    $existingMenu = DB::table('menus')->where('name', 'Field Agents')->first();
    
    if ($existingMenu) {
        echo "ℹ️  Field Agent menu already exists (ID: {$existingMenu->id}). Updating...\n";
        $parentMenuId = $existingMenu->id;
        
        // Delete old child menus
        $deleted = DB::table('menus')->where('parent_id', $parentMenuId)->delete();
        echo "   Deleted {$deleted} old child menus\n\n";
    } else {
        echo "📝 Creating Field Agent parent menu...\n";
        
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
        
        echo "✅ Parent menu created (ID: {$parentMenuId})\n\n";
    }
    
    // Insert child menus
    echo "📝 Creating child menus...\n";
    
    $childMenus = [
        [
            'name' => 'Dashboard',
            'title' => 'Dashboard',
            'url' => '/field-agent/dashboard',
            'icon' => 'far fa-circle',
            'permissions' => 'field_agent.agents.index',
            'order' => 0,
        ],
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
        echo "  ✅ Created: {$menu['name']}\n";
    }
    
    echo "\n✅ {$created} child menus created!\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎉 MENU SETUP COMPLETE!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "Summary:\n";
    echo "  ✅ 1 parent menu: Field Agents\n";
    echo "  ✅ {$created} child menus created\n\n";
    
    echo "Menu Structure:\n";
    echo "  📁 Field Agents\n";
    foreach ($childMenus as $menu) {
        echo "    └─ {$menu['name']}\n";
    }
    
    $success = true;
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    $success = false;
}

?>
    </div>
    
    <?php if ($success): ?>
    <div class="success">
        <strong>✅ Success!</strong> Field Agent menus have been added to your database.
    </div>
    
    <div class="info">
        <strong>Next Steps:</strong>
        <ol>
            <li>Clear your browser cache (Ctrl+F5)</li>
            <li>Log out and log back in</li>
            <li>Look for "Field Agents" in the navigation menu</li>
            <li><strong style="color: red;">DELETE THIS FILE (setup_field_agent_menus_web.php) for security!</strong></li>
        </ol>
    </div>
    
    <a href="/" class="button">🏠 Go to Dashboard</a>
    <a href="?confirm=yes" class="button">🔄 Run Again</a>
    
    <?php else: ?>
    <div class="error">
        <strong>❌ Error!</strong> Something went wrong. Check the log above for details.
    </div>
    
    <a href="?confirm=yes" class="button">🔄 Try Again</a>
    <a href="/" class="button danger">🏠 Go to Dashboard</a>
    <?php endif; ?>
    
    <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 5px;">
        <strong>⚠️ IMPORTANT SECURITY NOTICE:</strong><br>
        Delete this file immediately after use! It provides direct database access.
    </div>
</body>
</html>
