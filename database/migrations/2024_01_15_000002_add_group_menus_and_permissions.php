<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGroupMenusAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add group-related permissions
        $permissions = [
            // Group permissions
            ['name' => 'client.groups.index', 'guard_name' => 'web'],
            ['name' => 'client.groups.create', 'guard_name' => 'web'],
            ['name' => 'client.groups.edit', 'guard_name' => 'web'],
            ['name' => 'client.groups.destroy', 'guard_name' => 'web'],
            ['name' => 'client.groups.view', 'guard_name' => 'web'],
            ['name' => 'client.groups.manage_members', 'guard_name' => 'web'],
            
            // Group loan allocation permissions
            ['name' => 'loan.member-allocations.index', 'guard_name' => 'web'],
            ['name' => 'loan.member-allocations.create', 'guard_name' => 'web'],
            ['name' => 'loan.member-allocations.edit', 'guard_name' => 'web'],
            ['name' => 'loan.member-allocations.view', 'guard_name' => 'web'],
            ['name' => 'loan.member-allocations.payment', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Check if menus table exists and has required columns
        if (Schema::hasTable('menus')) {
            // Insert parent menu first
            $parentMenuId = DB::table('menus')->insertGetId([
                'name' => 'Groups',
                'title' => 'Groups',
                'parent_id' => null,
                'url' => '/client/group',
                'icon' => 'fas fa-users',
                'permissions' => 'client.groups.index',
                'menu_order' => 15,
                'is_parent' => 1,
                'module' => 'Client',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Insert child menus
            DB::table('menus')->insert([
                [
                    'name' => 'All Groups',
                    'title' => 'All Groups',
                    'parent_id' => $parentMenuId,
                    'url' => '/client/group',
                    'icon' => 'far fa-circle',
                    'permissions' => 'client.groups.index',
                    'menu_order' => 1,
                    'is_parent' => 0,
                    'module' => 'Client',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Create Group',
                    'title' => 'Create Group',
                    'parent_id' => $parentMenuId,
                    'url' => '/client/group/create',
                    'icon' => 'far fa-circle',
                    'permissions' => 'client.groups.create',
                    'menu_order' => 2,
                    'is_parent' => 0,
                    'module' => 'Client',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // Add group loan allocation submenu under Loans if it exists
        $loanMenu = DB::table('menus')->where('name', 'Loans')->first();
        if ($loanMenu) {
            DB::table('menus')->insertOrIgnore([
                'name' => 'Group Loan Allocations',
                'title' => 'Group Loan Allocations',
                'parent_id' => $loanMenu->id,
                'url' => '/loan/group-allocations',
                'icon' => 'far fa-circle',
                'permissions' => 'loan.member-allocations.index',
                'menu_order' => 10,
                'is_parent' => 0,
                'module' => 'Loan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove group menus
        DB::table('menus')->where('name', 'Groups')->delete();
        DB::table('menus')->where('name', 'All Groups')->delete();
        DB::table('menus')->where('name', 'Create Group')->delete();
        DB::table('menus')->where('name', 'Group Loan Allocations')->delete();

        // Remove permissions
        $permissions = [
            'client.groups.index',
            'client.groups.create',
            'client.groups.edit',
            'client.groups.destroy',
            'client.groups.view',
            'client.groups.manage_members',
            'loan.member-allocations.index',
            'loan.member-allocations.create',
            'loan.member-allocations.edit',
            'loan.member-allocations.view',
            'loan.member-allocations.payment',
        ];

        DB::table('permissions')->whereIn('name', $permissions)->delete();
    }
}
