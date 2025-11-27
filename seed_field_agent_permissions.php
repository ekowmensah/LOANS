<?php

/**
 * Field Agent Permissions Seeder
 * Run this file once to set up all permissions and roles
 * 
 * Usage: php seed_field_agent_permissions.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "🚀 Starting Field Agent Permissions Setup...\n\n";

// Create permissions
$permissions = [
    // Field Agent Management
    ['name' => 'field_agent.agents.index', 'display_name' => 'View Field Agents'],
    ['name' => 'field_agent.agents.create', 'display_name' => 'Create Field Agent'],
    ['name' => 'field_agent.agents.edit', 'display_name' => 'Edit Field Agent'],
    ['name' => 'field_agent.agents.destroy', 'display_name' => 'Delete Field Agent'],
    ['name' => 'field_agent.agents.view', 'display_name' => 'View Field Agent Details'],

    // Collection Management
    ['name' => 'field_agent.collections.index', 'display_name' => 'View Collections'],
    ['name' => 'field_agent.collections.create', 'display_name' => 'Record Collection'],
    ['name' => 'field_agent.collections.view', 'display_name' => 'View Collection Details'],
    ['name' => 'field_agent.collections.verify', 'display_name' => 'Verify Collection'],
    ['name' => 'field_agent.collections.post', 'display_name' => 'Post Collection'],
    ['name' => 'field_agent.collections.reject', 'display_name' => 'Reject Collection'],
    ['name' => 'field_agent.collections.view_own', 'display_name' => 'View Own Collections'],

    // Daily Report Management
    ['name' => 'field_agent.reports.index', 'display_name' => 'View Daily Reports'],
    ['name' => 'field_agent.reports.create', 'display_name' => 'Create Daily Report'],
    ['name' => 'field_agent.reports.view', 'display_name' => 'View Report Details'],
    ['name' => 'field_agent.reports.approve', 'display_name' => 'Approve Daily Report'],
    ['name' => 'field_agent.reports.reject', 'display_name' => 'Reject Daily Report'],
    ['name' => 'field_agent.reports.view_own', 'display_name' => 'View Own Reports'],

    // Dashboard & Analytics
    ['name' => 'field_agent.dashboard.view', 'display_name' => 'View Field Agent Dashboard'],
    ['name' => 'field_agent.analytics.view', 'display_name' => 'View Analytics'],
];

echo "📝 Creating " . count($permissions) . " permissions...\n";

$created = 0;
$existing = 0;

foreach ($permissions as $permission) {
    $perm = Permission::firstOrCreate(
        ['name' => $permission['name'], 'guard_name' => 'web']
    );
    
    if ($perm->wasRecentlyCreated) {
        $created++;
        echo "  ✅ Created: {$permission['name']}\n";
    } else {
        $existing++;
        echo "  ℹ️  Exists: {$permission['name']}\n";
    }
}

echo "\n✅ Permissions: {$created} created, {$existing} already existed\n\n";

// Assign to admin role
echo "👤 Assigning permissions to admin role...\n";
$adminRole = Role::where('name', 'admin')->first();

if ($adminRole) {
    $permissionNames = array_column($permissions, 'name');
    $adminRole->givePermissionTo($permissionNames);
    echo "✅ All permissions assigned to admin role!\n\n";
} else {
    echo "⚠️  Admin role not found. Skipping...\n\n";
}

// Create Field Agent Manager role
echo "👥 Creating Field Agent Manager role...\n";
$managerRole = Role::firstOrCreate(
    ['name' => 'field_agent_manager', 'guard_name' => 'web']
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
];

$managerRole->syncPermissions($managerPermissions);
echo "✅ Field Agent Manager role created with " . count($managerPermissions) . " permissions!\n\n";

// Create Field Agent role
echo "👤 Creating Field Agent role...\n";
$agentRole = Role::firstOrCreate(
    ['name' => 'field_agent', 'guard_name' => 'web']
);

$agentPermissions = [
    'field_agent.collections.create',
    'field_agent.collections.view_own',
    'field_agent.reports.create',
    'field_agent.reports.view_own',
];

$agentRole->syncPermissions($agentPermissions);
echo "✅ Field Agent role created with " . count($agentPermissions) . " permissions!\n\n";

// Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎉 SETUP COMPLETE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "Summary:\n";
echo "  ✅ " . count($permissions) . " permissions created\n";
echo "  ✅ 3 roles configured:\n";
echo "     - admin (all permissions)\n";
echo "     - field_agent_manager (" . count($managerPermissions) . " permissions)\n";
echo "     - field_agent (" . count($agentPermissions) . " permissions)\n\n";

echo "Next Steps:\n";
echo "  1. Clear cache: php artisan cache:clear\n";
echo "  2. Create upload directories\n";
echo "  3. Create your first field agent\n";
echo "  4. Test the system!\n\n";

echo "Access URLs:\n";
echo "  - Field Agents: /field-agent/agent\n";
echo "  - Collections: /field-agent/collection\n";
echo "  - Daily Reports: /field-agent/daily-report\n\n";

echo "✨ Your Field Agent System is ready to use!\n\n";
