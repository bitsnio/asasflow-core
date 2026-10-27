<?php

declare(strict_types=1);

return [
    'module' => [
        'name' => 'Core',
        'title' => 'System Administration',

        'middleware' => [
            'auth:api',
        ],

        'children' => [

            /*
            |--------------------------------------------------------------------------
            | Tenant Management
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'tenancy',
                'title' => 'Tenant Management',

                'children' => [
                    [
                        'name' => 'tenants',
                        'title' => 'Tenants',
                        'type' => 'resource',
                        'model' => true,

                        'permissions' => [
                            'view' => 'View tenants',
                            'create' => 'Register tenants',
                            'update' => 'Update tenants',
                            'delete' => 'Delete tenants',
                        ],

                        'actions' => [
                            'activate' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Activate tenants',
                            ],
                            'suspend' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Suspend tenants',
                            ]
                        ],
                    ],

                    [
                        'name' => 'tenant-domains',
                        'title' => 'Tenant Domains',
                        'type' => 'resource',
                        'model' => true,

                        'permissions' => [
                            'view' => 'View tenant domains',
                            'create' => 'Add tenant domain',
                            'update' => 'Update tenant domain',
                            'delete' => 'Delete tenant domain',
                        ],

                        'actions' => [
                            'verify' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Verify domain ownership',
                            ],
                        ],
                    ],

                    [
                        'name' => 'sites',
                        'title' => 'Sites & Branches',
                        'type' => 'resource',
                        'model' => true,
                        'permissions' => [
                            'view' => 'View sites',
                            'create' => 'Create sites',
                            'update' => 'Update sites',
                            'delete' => 'Delete sites',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Access Management
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'access',
                'title' => 'Access Management',

                'children' => [
                    [
                        'name' => 'users',
                        'title' => 'Users',
                        'type' => 'resource',
                        'model' => true, // Receives tenant_id & site_id automatically

                        'permissions' => [
                            'view' => 'View users',
                            'create' => 'Create users',
                            'update' => 'Update users',
                            'delete' => 'Delete users',
                        ],

                        'actions' => [
                           
                            'activate' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Activate users',
                            ],
                            'deactivate' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Deactivate users',
                            ],
                            'assignRoles' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Assign roles to user',
                            ],
                            'login' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Login as user',
                            ],
                            'logout' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Logout user',
                            ],
                            'register' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Register user',
                            ],
                            'forgotPassword' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Forgot password',
                            ],
                            'resetPassword' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Reset user passwords',
                            ],
                        ],
                    ],

                    [
                        'name' => 'roles',
                        'title' => 'Roles Management',
                        'type' => 'resource',
                        'model' => true,
                        'tenancy' => true, // Tenant-scoped: each tenant has their own roles
                        'site' => false,    // Roles apply across all sites of a tenant

                        'permissions' => [
                            'view' => 'View roles',
                            'create' => 'Create roles',
                            'update' => 'Update roles',
                            'delete' => 'Delete roles',
                        ],

                        'actions' => [
                            'assignPermissions' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Assign permissions to role',
                            ],
                        ],
                    ],

                    [
                        'name' => 'permissions',
                        'title' => 'Permissions Management',
                        'type' => 'resource',
                        'model' => true,
                        'tenancy' => false, // Global system permissions (no tenant_id)
                        'site' => false,

                        'permissions' => [
                            'view' => 'View permissions',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | System Configuration
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'configuration',
                'title' => 'Configuration',

                'children' => [
                    [
                        'name' => 'settings',
                        'title' => 'Settings',
                        'type' => 'resource',
                        'model' => false,

                        'permissions' => [
                            'view' => 'View system settings',
                            'update' => 'Update system settings',
                        ],
                    ],
                ],
            ],
        ],
    ],
];