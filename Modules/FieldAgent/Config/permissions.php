<?php

return [
    // Field Agent Management
    [
        'name' => 'field_agent.agents.index',
        'display_name' => 'View Field Agents',
        'description' => 'View list of field agents',
    ],
    [
        'name' => 'field_agent.agents.create',
        'display_name' => 'Create Field Agent',
        'description' => 'Create new field agent',
    ],
    [
        'name' => 'field_agent.agents.edit',
        'display_name' => 'Edit Field Agent',
        'description' => 'Edit field agent details',
    ],
    [
        'name' => 'field_agent.agents.destroy',
        'display_name' => 'Delete Field Agent',
        'description' => 'Delete field agent',
    ],
    [
        'name' => 'field_agent.agents.view',
        'display_name' => 'View Field Agent Details',
        'description' => 'View detailed information about a field agent',
    ],

    // Field Collection Management
    [
        'name' => 'field_agent.collections.index',
        'display_name' => 'View Collections',
        'description' => 'View list of field collections',
    ],
    [
        'name' => 'field_agent.collections.create',
        'display_name' => 'Record Collection',
        'description' => 'Record new field collection',
    ],
    [
        'name' => 'field_agent.collections.view',
        'display_name' => 'View Collection Details',
        'description' => 'View detailed information about a collection',
    ],
    [
        'name' => 'field_agent.collections.verify',
        'display_name' => 'Verify Collection',
        'description' => 'Verify field collections',
    ],
    [
        'name' => 'field_agent.collections.post',
        'display_name' => 'Post Collection',
        'description' => 'Post verified collections to accounting',
    ],
    [
        'name' => 'field_agent.collections.reject',
        'display_name' => 'Reject Collection',
        'description' => 'Reject field collections',
    ],
    [
        'name' => 'field_agent.collections.view_own',
        'display_name' => 'View Own Collections',
        'description' => 'View own collections only (for field agents)',
    ],

    // Daily Report Management
    [
        'name' => 'field_agent.reports.index',
        'display_name' => 'View Daily Reports',
        'description' => 'View list of daily reports',
    ],
    [
        'name' => 'field_agent.reports.create',
        'display_name' => 'Create Daily Report',
        'description' => 'Create new daily report',
    ],
    [
        'name' => 'field_agent.reports.view',
        'display_name' => 'View Report Details',
        'description' => 'View detailed information about a daily report',
    ],
    [
        'name' => 'field_agent.reports.approve',
        'display_name' => 'Approve Daily Report',
        'description' => 'Approve daily reports',
    ],
    [
        'name' => 'field_agent.reports.reject',
        'display_name' => 'Reject Daily Report',
        'description' => 'Reject daily reports',
    ],
    [
        'name' => 'field_agent.reports.view_own',
        'display_name' => 'View Own Reports',
        'description' => 'View own reports only (for field agents)',
    ],

    // Dashboard & Analytics
    [
        'name' => 'field_agent.dashboard.view',
        'display_name' => 'View Field Agent Dashboard',
        'description' => 'View field agent dashboard and analytics',
    ],
    [
        'name' => 'field_agent.analytics.view',
        'display_name' => 'View Analytics',
        'description' => 'View field agent performance analytics',
    ],
];
