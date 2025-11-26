<?php
/**
 * Teller Module Production Setup
 * Upload this to your server and access via browser: yourdomain.com/teller_production_setup.php
 * DELETE THIS FILE after use!
 */

// Security password - change this!
$password = 'TellerSetup2025';

if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    die('Unauthorized access');
}

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Core\Entities\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "<h1>Teller Module Production Setup</h1>";
echo "<pre>";

try {
    // Check if menu already exists
    $existingMenu = Menu::where('slug', 'teller')->first();
    if ($existingMenu) {
        echo "✓ Teller menu already exists (ID: {$existingMenu->id})\n";
    } else {
        // Create menu
        $tellerMenu = new Menu();
        $tellerMenu->name = 'Teller';
        $tellerMenu->is_parent = 0;
        $tellerMenu->module = 'Teller';
        $tellerMenu->slug = 'teller';
        $tellerMenu->parent_slug = '';
        $tellerMenu->parent_id = null;
        $tellerMenu->url = 'teller';
        $tellerMenu->icon = 'fas fa-cash-register';
        $tellerMenu->menu_order = 8;
        $tellerMenu->permissions = 'teller.teller.index';
        $tellerMenu->save();
        echo "✓ Teller menu created (ID: {$tellerMenu->id})\n";
    }

    // Create permissions
    $permissions = [
        [
            'name' => 'teller.teller.index',
            'display_name' => 'View Teller',
            'module' => 'Teller'
        ],
        [
            'name' => 'teller.teller.transactions.create',
            'display_name' => 'Process Teller Transactions',
            'module' => 'Teller'
        ],
    ];

    foreach ($permissions as $permData) {
        $perm = Permission::where('name', $permData['name'])->first();
        if (!$perm) {
            $perm = new Permission();
            $perm->name = $permData['name'];
            $perm->display_name = $permData['display_name'];
            $perm->module = $permData['module'];
            $perm->guard_name = 'web';
            $perm->save();
            echo "✓ Permission created: {$permData['name']}\n";
        } else {
            echo "✓ Permission exists: {$permData['name']}\n";
        }
    }

    // Assign to admin role
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        $tellerPermissions = Permission::where('name', 'like', 'teller.%')->get();
        foreach ($tellerPermissions as $permission) {
            if (!$adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
                echo "✓ Assigned {$permission->name} to admin role\n";
            } else {
                echo "✓ Admin already has {$permission->name}\n";
            }
        }
    } else {
        echo "⚠ Warning: Admin role not found!\n";
    }

    // Clear cache
    \Artisan::call('cache:clear');
    echo "✓ Cache cleared\n";
    
    \Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
    
    \Artisan::call('view:clear');
    echo "✓ View cache cleared\n";

    echo "\n";
    echo "========================================\n";
    echo "✓ SETUP COMPLETE!\n";
    echo "========================================\n";
    echo "\n";
    echo "Next steps:\n";
    echo "1. DELETE this file (teller_production_setup.php) immediately!\n";
    echo "2. Logout and login again\n";
    echo "3. Check if Teller menu appears in navigation\n";
    echo "4. Access: " . url('teller') . "\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
?>
