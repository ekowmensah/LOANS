<?php

return [
    // Parent Menu
    [
        'name' => 'Field Agents',
        'title' => 'Field Agents',
        'is_parent' => 1,
        'url' => '/field-agent',
        'icon' => 'fas fa-walking',
        'permissions' => 'field_agent.agents.index',
        'menu_order' => 50,
        'module' => 'FieldAgent',
        'children' => [
            // Dashboard
            [
                'name' => 'Dashboard',
                'title' => 'Dashboard',
                'url' => '/field-agent/dashboard',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.agents.index',
                'menu_order' => 0,
            ],
            
            // Field Agents Management
            [
                'name' => 'All Field Agents',
                'title' => 'All Field Agents',
                'url' => '/field-agent/agent',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.agents.index',
                'menu_order' => 1,
            ],
            [
                'name' => 'Add Field Agent',
                'title' => 'Add Field Agent',
                'url' => '/field-agent/agent/create',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.agents.create',
                'menu_order' => 2,
            ],
            
            // Collections
            [
                'name' => 'Field Collections',
                'title' => 'Field Collections',
                'url' => '/field-agent/collection',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.collections.index',
                'menu_order' => 3,
            ],
            [
                'name' => 'Record Collection',
                'title' => 'Record Collection',
                'url' => '/field-agent/collection/create',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.collections.create',
                'menu_order' => 4,
            ],
            [
                'name' => 'Verify Collections',
                'title' => 'Verify Collections',
                'url' => '/field-agent/collection/verify',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.collections.verify',
                'menu_order' => 5,
            ],
            
            // Daily Reports
            [
                'name' => 'Daily Reports',
                'title' => 'Daily Reports',
                'url' => '/field-agent/daily-report',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.reports.index',
                'menu_order' => 6,
            ],
            [
                'name' => 'Submit Daily Report',
                'title' => 'Submit Daily Report',
                'url' => '/field-agent/daily-report/create',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.reports.create',
                'menu_order' => 7,
            ],
            
            // Analytics
            [
                'name' => 'Performance Analytics',
                'title' => 'Performance Analytics',
                'url' => '/field-agent/analytics',
                'icon' => 'far fa-circle',
                'permissions' => 'field_agent.analytics.view',
                'menu_order' => 8,
            ],
        ],
    ],
];
