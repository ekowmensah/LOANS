<?php
/**
 * Web-based Field Agent Permissions Setup
 * Upload this file to your server root and access via browser
 * URL: https://yourdomain.com/setup_field_agent_permissions_web.php
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
        <title>Field Agent Permissions Setup</title>
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
        <h1>🔐 Field Agent Permissions Setup</h1>
        
        <div class="warning">
            <strong>⚠️ Warning:</strong> This will add Field Agent permissions to your database.
        </div>
        
        <h2>What this will do:</h2>
        <ul>
            <li>✅ Create 12 Field Agent permissions</li>
            <li>✅ Assign permissions to admin role</li>
            <li>✅ Skip if permissions already exist</li>
        </ul>
        
        <h2>Permissions to be created:</h2>
        <ul>
            <li>field_agent.agents.index</li>
            <li>field_agent.agents.create</li>
            <li>field_agent.agents.update</li>
            <li>field_agent.agents.delete</li>
            <li>field_agent.collections.index</li>
            <li>field_agent.collections.create</li>
            <li>field_agent.collections.verify</li>
            <li>field_agent.reports.index</li>
            <li>field_agent.reports.create</li>
            <li>field_agent.analytics.view</li>
        </ul>
        
        <a href="?confirm=yes" class="button">✅ Yes, Setup Permissions</a>
        <a href="/" class="button danger">❌ Cancel</a>
        
        <p style="color: #666; margin-top: 30px;">
            <strong>Note:</strong> Run this BEFORE setting up menus. Delete this file after running!
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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Field Agent Permissions Setup - Results</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .log { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; font-size: 14px; white-space: pre-wrap; }
        h1 { color: #333; }
        .button { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .button:hover { background: #0056b3; }
        .danger { background: #dc3545; }
        .danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <h1>🔐 Field Agent Permissions Setup</h1>
    
    <div class="log"><?php

try {
    echo "Starting Field Agent Permissions Setup...\n\n";
    
    // Define permissions
    $permissions = [
        // Agent Management
        ['name' => 'field_agent.agents.index', 'display_name' => 'View Field Agents', 'description' => 'View list of field agents'],
        ['name' => 'field_agent.agents.create', 'display_name' => 'Create Field Agent', 'description' => 'Create new field agent'],
        ['name' => 'field_agent.agents.edit', 'display_name' => 'Edit Field Agent', 'description' => 'Edit field agent details'],
        ['name' => 'field_agent.agents.view', 'display_name' => 'View Field Agent Details', 'description' => 'View single field agent'],
        ['name' => 'field_agent.agents.destroy', 'display_name' => 'Delete Field Agent', 'description' => 'Delete field agent'],
        
        // Collections
        ['name' => 'field_agent.collections.index', 'display_name' => 'View Collections', 'description' => 'View field collections'],
        ['name' => 'field_agent.collections.create', 'display_name' => 'Record Collection', 'description' => 'Record new collection'],
        ['name' => 'field_agent.collections.view', 'display_name' => 'View Collection Details', 'description' => 'View single collection'],
        ['name' => 'field_agent.collections.verify', 'display_name' => 'Verify Collections', 'description' => 'Verify field collections'],
        ['name' => 'field_agent.collections.post', 'display_name' => 'Post Collections', 'description' => 'Post collections to accounting'],
        
        // Reports
        ['name' => 'field_agent.reports.index', 'display_name' => 'View Daily Reports', 'description' => 'View field agent daily reports'],
        ['name' => 'field_agent.reports.create', 'display_name' => 'Submit Daily Report', 'description' => 'Submit daily report'],
        ['name' => 'field_agent.reports.view', 'display_name' => 'View Report Details', 'description' => 'View single daily report'],
        ['name' => 'field_agent.reports.approve', 'display_name' => 'Approve Reports', 'description' => 'Approve daily reports'],
        
        // Analytics
        ['name' => 'field_agent.analytics.view', 'display_name' => 'View Analytics', 'description' => 'View field agent performance analytics'],
    ];
    
    $created = 0;
    $skipped = 0;
    $permissionIds = [];
    
    foreach ($permissions as $perm) {
        // Check if permission already exists
        $existing = Permission::where('name', $perm['name'])->first();
        
        if ($existing) {
            echo "⏭️  Skipped (exists): {$perm['name']}\n";
            $skipped++;
            $permissionIds[] = $existing->id;
        } else {
            $permission = Permission::create([
                'name' => $perm['name'],
                'display_name' => $perm['display_name'],
                'description' => $perm['description'],
                'guard_name' => 'web',
            ]);
            echo "✅ Created: {$perm['name']}\n";
            $created++;
            $permissionIds[] = $permission->id;
        }
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Assigning permissions to roles...\n\n";
    
    // Find or create field agent role
    $fieldAgentRole = Role::where('name', 'field_agent')->first();
    if (!$fieldAgentRole) {
        $fieldAgentRole = Role::create([
            'name' => 'field_agent',
            'display_name' => 'Field Agent',
            'description' => 'Field agent role with collection and reporting permissions',
            'guard_name' => 'web',
        ]);
        echo "✅ Created 'field_agent' role\n";
    } else {
        echo "⏭️  'field_agent' role already exists\n";
    }
    
    // Field agent specific permissions (not all permissions)
    $fieldAgentPermissions = [
        'field_agent.agents.index',
        'field_agent.collections.index',
        'field_agent.collections.create',
        'field_agent.collections.view',
        'field_agent.reports.index',
        'field_agent.reports.create',
        'field_agent.reports.view',
    ];
    
    foreach ($permissions as $perm) {
        if (in_array($perm['name'], $fieldAgentPermissions)) {
            $permission = Permission::where('name', $perm['name'])->first();
            if ($permission && !$fieldAgentRole->hasPermissionTo($permission)) {
                $fieldAgentRole->givePermissionTo($permission);
            }
        }
    }
    echo "✅ Assigned field agent permissions to 'field_agent' role\n\n";
    
    // Find admin role
    $adminRole = Role::where('name', 'admin')->orWhere('name', 'super_admin')->first();
    
    if ($adminRole) {
        // Assign all permissions to admin role
        foreach ($permissionIds as $permId) {
            // Check if already assigned
            $exists = DB::table('role_has_permissions')
                ->where('role_id', $adminRole->id)
                ->where('permission_id', $permId)
                ->exists();
            
            if (!$exists) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permId,
                ]);
            }
        }
        echo "✅ Assigned all permissions to '{$adminRole->name}' role\n\n";
    } else {
        echo "⚠️  Warning: Admin role not found. You'll need to assign permissions manually.\n\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎉 PERMISSIONS SETUP COMPLETE!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "Summary:\n";
    echo "  ✅ {$created} new permissions created\n";
    echo "  ⏭️  {$skipped} permissions already existed\n";
    echo "  📊 Total: " . count($permissions) . " permissions\n\n";
    
    $success = true;
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    $success = false;
}

?></div>
    
    <?php if ($success): ?>
    <div class="success">
        <strong>✅ Success!</strong> Field Agent permissions have been set up.
    </div>
    
    <div class="info">
        <strong>Next Steps:</strong>
        <ol>
            <li>Now run the menu setup: <a href="setup_field_agent_menus_web.php">setup_field_agent_menus_web.php</a></li>
            <li>Clear your browser cache (Ctrl+F5)</li>
            <li>Log out and log back in</li>
            <li><strong style="color: red;">DELETE BOTH setup files for security!</strong></li>
        </ol>
    </div>
    
    <a href="setup_field_agent_menus_web.php" class="button">➡️ Setup Menus Next</a>
    <a href="/" class="button">🏠 Go to Dashboard</a>
    
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
