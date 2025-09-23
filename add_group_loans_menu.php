<?php
/**
 * Script to add Group Loans submenu items to the existing Loans menu
 * Run this once to add Individual/Group loan filters to the navigation
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Core\Entities\Menu;

// Find the existing Loans parent menu
$loansParent = Menu::where('name', 'Loans')->where('is_parent', 1)->first();

if (!$loansParent) {
    echo "Error: Loans parent menu not found!\n";
    exit(1);
}

echo "Found Loans parent menu (ID: {$loansParent->id})\n";

// Get the highest menu_order for Loans submenus to insert new items properly
$maxOrder = Menu::where('parent_id', $loansParent->id)->max('menu_order') ?? 19;

// Add Individual Loans submenu
$individualLoansMenu = new Menu();
$individualLoansMenu->name = 'Individual Loans';
$individualLoansMenu->is_parent = 0;
$individualLoansMenu->module = 'Loan';
$individualLoansMenu->slug = 'individual_loans';
$individualLoansMenu->parent_slug = 'loans';
$individualLoansMenu->parent_id = $loansParent->id;
$individualLoansMenu->url = 'loan?client_type=client';
$individualLoansMenu->icon = 'far fa-circle';
$individualLoansMenu->menu_order = $maxOrder + 0.1;
$individualLoansMenu->permissions = 'loan.loans.index';
$individualLoansMenu->save();

// Add Group Loans submenu
$groupLoansMenu = new Menu();
$groupLoansMenu->name = 'Group Loans';
$groupLoansMenu->is_parent = 0;
$groupLoansMenu->module = 'Loan';
$groupLoansMenu->slug = 'group_loans';
$groupLoansMenu->parent_slug = 'loans';
$groupLoansMenu->parent_id = $loansParent->id;
$groupLoansMenu->url = 'loan?client_type=group';
$groupLoansMenu->icon = 'far fa-circle';
$groupLoansMenu->menu_order = $maxOrder + 0.2;
$groupLoansMenu->permissions = 'loan.loans.index';
$groupLoansMenu->save();

echo "Group loan menu items added successfully!\n";
echo "- Individual Loans (ID: {$individualLoansMenu->id})\n";
echo "- Group Loans (ID: {$groupLoansMenu->id})\n";
