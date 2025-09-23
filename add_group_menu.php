<?php
/**
 * Script to add Groups menu items to the navigation
 * Based on the successful pattern from add_group_loans_menu.php
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Core\Entities\Menu;

// Add Groups parent menu
$groupsParent = new Menu();
$groupsParent->name = 'Groups';
$groupsParent->is_parent = 1;
$groupsParent->module = 'Client';
$groupsParent->slug = 'groups';
$groupsParent->parent_slug = '';
$groupsParent->parent_id = null;
$groupsParent->url = 'client/group';
$groupsParent->icon = 'fas fa-users-cog';
$groupsParent->menu_order = 15;
$groupsParent->permissions = 'client.groups.index';
$groupsParent->save();

echo "Groups parent menu created (ID: {$groupsParent->id})\n";

// Add View Groups submenu
$viewGroupsMenu = new Menu();
$viewGroupsMenu->name = 'View Groups';
$viewGroupsMenu->is_parent = 0;
$viewGroupsMenu->module = 'Client';
$viewGroupsMenu->slug = 'view_groups';
$viewGroupsMenu->parent_slug = 'groups';
$viewGroupsMenu->parent_id = $groupsParent->id;
$viewGroupsMenu->url = 'client/group';
$viewGroupsMenu->icon = 'far fa-circle';
$viewGroupsMenu->menu_order = 15.1;
$viewGroupsMenu->permissions = 'client.groups.index';
$viewGroupsMenu->save();

// Add Create Group submenu
$createGroupMenu = new Menu();
$createGroupMenu->name = 'Create Group';
$createGroupMenu->is_parent = 0;
$createGroupMenu->module = 'Client';
$createGroupMenu->slug = 'create_group';
$createGroupMenu->parent_slug = 'groups';
$createGroupMenu->parent_id = $groupsParent->id;
$createGroupMenu->url = 'client/group/create';
$createGroupMenu->icon = 'far fa-circle';
$createGroupMenu->menu_order = 15.2;
$createGroupMenu->permissions = 'client.groups.create';
$createGroupMenu->save();

echo "Group menu items added successfully!\n";
echo "- View Groups (ID: {$viewGroupsMenu->id})\n";
echo "- Create Group (ID: {$createGroupMenu->id})\n";
