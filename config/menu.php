<?php

return [
    'module' => [
        'name' => 'Core',
        'title' => 'System Administration',

        'middleware' => [
            'asasflow.permission',
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
                            ],
                            'provision' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Provision tenant resources',
                            ],
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
                        'model' => true,

                        'permissions' => [
                            'view' => 'View users',
                            'create' => 'Create users',
                            'update' => 'Update users',
                            'delete' => 'Delete users',
                        ],

                        'actions' => [
                            'resetPassword' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Reset user passwords',
                            ],
                            'activate' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Activate users',
                            ],
                            'deactivate' => [
                                'method' => 'POST',
                                'permissionLabel' => 'Deactivate users',
                            ],
                            'login'=> [
                                'method' => 'POST',
                                'permissionLabel' => 'Login as user',
                            ],
                            'logout'=> [
                                'method' => 'POST',
                                'permissionLabel' => 'Logout user',
                            ],
                            'register'=> [
                                'method' => 'POST',
                                'permissionLabel' => 'Register user',
                            ],
                        ],
                    ],

                    [
                        'name' => 'roles',
                        'title' => 'Roles Management',
                        'type' => 'resource',
                        'model' => true,

                        'permissions' => [
                            'view' => 'View roles',
                            'create' => 'Create roles',
                            'update' => 'Update roles',
                            'delete' => 'Delete roles',
                        ],
                    ],

                    [
                        'name' => 'permissions',
                        'title' => 'Permissions Management',
                        'type' => 'resource',
                        'model' => true,

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