<?php

/**
 * Field Agent Menu Seeder
 * Run this file to add Field Agent menus to navigation
 * 
 * Usage: php seed_field_agent_menus.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🚀 Starting Field Agent Menu Setup...\n\n";

// Check if menus table exists
if (!DB::getSchemaBuilder()->hasTable('menus')) {
    echo "❌ Error: 'menus' table does not exist!\n";
    exit(1);
}

// Check if Field Agent menu already exists
$existingMenu = DB::table('menus')->where('name', 'Field Agents')->first();

if ($existingMenu) {
    echo "ℹ️  Field Agent menu already exists. Updating...\n\n";
    $parentMenuId = $existingMenu->id;
    
    // Delete old child menus
    DB::table('menus')->where('parent_id', $parentMenuId)->delete();
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

// Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎉 MENU SETUP COMPLETE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Summary:\n";
echo "  ✅ 1 parent menu created: Field Agents\n";
echo "  ✅ {$created} child menus created\n\n";

echo "Menu Structure:\n";
echo "  📁 Field Agents\n";
foreach ($childMenus as $menu) {
    echo "    └─ {$menu['name']}\n";
}

echo "\nNext Steps:\n";
echo "  1. Clear cache: php artisan cache:clear\n";
echo "  2. Refresh your browser\n";
echo "  3. Look for 'Field Agents' in the navigation menu\n\n";

echo "✨ Field Agent menu is now in your navigation!\n\n";
