<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGroupPermissionsOnly extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add group-related permissions only
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
            // Check if permission already exists
            $exists = DB::table('permissions')
                ->where('name', $permission['name'])
                ->where('guard_name', $permission['guard_name'])
                ->exists();
                
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
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
