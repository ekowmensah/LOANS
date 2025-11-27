<?php

namespace Modules\FieldAgent\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FieldAgentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            // Field Agent Management
            ['name' => 'field_agent.agents.index', 'display_name' => 'View Field Agents', 'guard_name' => 'web'],
            ['name' => 'field_agent.agents.create', 'display_name' => 'Create Field Agent', 'guard_name' => 'web'],
            ['name' => 'field_agent.agents.edit', 'display_name' => 'Edit Field Agent', 'guard_name' => 'web'],
            ['name' => 'field_agent.agents.destroy', 'display_name' => 'Delete Field Agent', 'guard_name' => 'web'],
            ['name' => 'field_agent.agents.view', 'display_name' => 'View Field Agent Details', 'guard_name' => 'web'],

            // Collection Management
            ['name' => 'field_agent.collections.index', 'display_name' => 'View Collections', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.create', 'display_name' => 'Record Collection', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.view', 'display_name' => 'View Collection Details', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.verify', 'display_name' => 'Verify Collection', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.post', 'display_name' => 'Post Collection', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.reject', 'display_name' => 'Reject Collection', 'guard_name' => 'web'],
            ['name' => 'field_agent.collections.view_own', 'display_name' => 'View Own Collections', 'guard_name' => 'web'],

            // Daily Report Management
            ['name' => 'field_agent.reports.index', 'display_name' => 'View Daily Reports', 'guard_name' => 'web'],
            ['name' => 'field_agent.reports.create', 'display_name' => 'Create Daily Report', 'guard_name' => 'web'],
            ['name' => 'field_agent.reports.view', 'display_name' => 'View Report Details', 'guard_name' => 'web'],
            ['name' => 'field_agent.reports.approve', 'display_name' => 'Approve Daily Report', 'guard_name' => 'web'],
            ['name' => 'field_agent.reports.reject', 'display_name' => 'Reject Daily Report', 'guard_name' => 'web'],
            ['name' => 'field_agent.reports.view_own', 'display_name' => 'View Own Reports', 'guard_name' => 'web'],

            // Dashboard & Analytics
            ['name' => 'field_agent.dashboard.view', 'display_name' => 'View Field Agent Dashboard', 'guard_name' => 'web'],
            ['name' => 'field_agent.analytics.view', 'display_name' => 'View Analytics', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['display_name' => $permission['display_name']]
            );
        }

        $this->command->info('Field Agent permissions created successfully!');

        // Assign all permissions to admin role if it exists
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $permissionNames = array_column($permissions, 'name');
            $adminRole->givePermissionTo($permissionNames);
            $this->command->info('Permissions assigned to admin role!');
        }

        // Create Field Agent Manager role
        $managerRole = Role::firstOrCreate(
            ['name' => 'field_agent_manager', 'guard_name' => 'web'],
            ['display_name' => 'Field Agent Manager']
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
        $this->command->info('Field Agent Manager role created!');

        // Create Field Agent role
        $agentRole = Role::firstOrCreate(
            ['name' => 'field_agent', 'guard_name' => 'web'],
            ['display_name' => 'Field Agent']
        );

        $agentPermissions = [
            'field_agent.collections.create',
            'field_agent.collections.view_own',
            'field_agent.reports.create',
            'field_agent.reports.view_own',
        ];

        $agentRole->syncPermissions($agentPermissions);
        $this->command->info('Field Agent role created!');

        $this->command->info('✅ All Field Agent permissions and roles created successfully!');
    }
}
