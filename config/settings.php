<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Core Module Settings
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'maintenance_mode' => [
            'label' => 'Maintenance Mode',
            'description' => 'Put the application into maintenance mode.',
            'type' => 'boolean',
            'input' => 'switch',
            'default' => false,
            'scope' => 'module',
            'options' => [],
            'rules' => ['boolean'],
        ],

        'default_currency' => [
            'label' => 'Default Currency',
            'description' => 'Primary currency for transactions.',
            'type' => 'string',
            'input' => 'select',
            'default' => 'USD',
            'scope' => 'company',
            'options' => [
                ['label' => 'US Dollar', 'value' => 'USD'],
                ['label' => 'Pakistani Rupee', 'value' => 'PKR'],
                ['label' => 'Euro', 'value' => 'EUR'],
                ['label' => 'UAE Dirham', 'value' => 'AED'],
            ],
            'rules' => ['required', 'string', 'in:USD,PKR,EUR,AED'],
        ],

        'date_format' => [
            'label' => 'Date Format',
            'description' => 'Default display format for dates.',
            'type' => 'string',
            'input' => 'select',
            'default' => 'Y-m-d',
            'scope' => 'site',
            'options' => [
                ['label' => 'YYYY-MM-DD', 'value' => 'Y-m-d'],
                ['label' => 'DD/MM/YYYY', 'value' => 'd/m/Y'],
                ['label' => 'MM/DD/YYYY', 'value' => 'm/d/Y'],
            ],
            'rules' => ['required', 'string'],
        ],
    ],

];
