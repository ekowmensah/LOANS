<?php

return [
    ['name' => 'Clients', 'is_parent' => 1, 'module' => 'Client', 'slug' => 'clients', 'parent_slug' => '', 'url' => 'client', 'icon' => 'fas fa-users', 'order' => 10, 'permissions' => 'client.clients.index'],
    ['name' => 'View Clients', 'is_parent' => 0, 'module' => 'Client', 'slug' => 'view_clients', 'parent_slug' => 'clients', 'url' => 'client', 'icon' => 'far fa-circle', 'order' => 11, 'permissions' => 'client.clients.index'],
    ['name' => 'Create Client', 'is_parent' => 0, 'module' => 'Client', 'slug' => 'create_client', 'parent_slug' => 'clients', 'url' => 'client/create', 'icon' => 'far fa-circle', 'order' => 12, 'permissions' => 'client.clients.create'],
    
    // Groups menu
    ['name' => 'Groups', 'is_parent' => 1, 'module' => 'Client', 'slug' => 'groups', 'parent_slug' => '', 'url' => 'client/group', 'icon' => 'fas fa-users-cog', 'order' => 15, 'permissions' => 'client.groups.index'],
    ['name' => 'View Groups', 'is_parent' => 0, 'module' => 'Client', 'slug' => 'view_groups', 'parent_slug' => 'groups', 'url' => 'client/group', 'icon' => 'far fa-circle', 'order' => 16, 'permissions' => 'client.groups.index'],
    ['name' => 'Create Group', 'is_parent' => 0, 'module' => 'Client', 'slug' => 'create_group', 'parent_slug' => 'groups', 'url' => 'client/group/create', 'icon' => 'far fa-circle', 'order' => 17, 'permissions' => 'client.groups.create'],
];