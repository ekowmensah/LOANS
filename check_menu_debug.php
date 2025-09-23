<?php
/**
 * Debug script to check menu items and permissions
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Core\Entities\Menu;
use Illuminate\Support\Facades\Auth;

echo "=== MENU DEBUG INFORMATION ===\n\n";

// Check if Groups menu exists
echo "1. Checking Groups menu in database:\n";
$groupsMenus = Menu::where('name', 'Groups')->get(['id', 'name', 'is_parent', 'permissions', 'menu_order', 'url']);
if ($groupsMenus->count() > 0) {
    foreach ($groupsMenus as $menu) {
        echo "   - ID: {$menu->id}, Name: {$menu->name}, Parent: {$menu->is_parent}, Permission: {$menu->permissions}, Order: {$menu->menu_order}, URL: {$menu->url}\n";
    }
} else {
    echo "   - No Groups menu found!\n";
}

// Check all parent menus
echo "\n2. All parent menus (is_parent = 1):\n";
$parentMenus = Menu::where('is_parent', 1)->orderBy('menu_order')->get(['id', 'name', 'permissions', 'menu_order']);
foreach ($parentMenus as $menu) {
    echo "   - ID: {$menu->id}, Name: {$menu->name}, Permission: {$menu->permissions}, Order: {$menu->menu_order}\n";
}

// Check if user is authenticated
echo "\n3. Authentication status:\n";
if (Auth::check()) {
    $user = Auth::user();
    echo "   - User authenticated: {$user->first_name} {$user->last_name} (ID: {$user->id})\n";
    
    // Check user permissions
    echo "\n4. User permissions check:\n";
    $permissions = ['client.groups.index', 'client.groups.create', 'client.groups.edit'];
    foreach ($permissions as $permission) {
        $hasPermission = $user->can($permission) ? 'YES' : 'NO';
        echo "   - {$permission}: {$hasPermission}\n";
    }
} else {
    echo "   - No user authenticated\n";
}

echo "\n=== END DEBUG ===\n";
