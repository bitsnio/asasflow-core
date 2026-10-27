<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Users;
use Modules\Core\Models\Sites;
use Bitsnio\AsasFlow\Features\Tenancy\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Create Default Tenant
            $tenant = Tenant::updateOrCreate(
                ['slug' => 'primary'],
                [
                    'name'      => 'Primary Organization',
                    'domain'    => 'primary.localhost',
                    'database'  => null,
                    'settings'  => [
                        'timezone' => 'UTC',
                        'currency' => 'USD',
                    ],
                    'plan'      => 'enterprise',
                    'is_active' => true,
                ]
            );

            // 2. Create Default Primary Site
            $site = Sites::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'code'      => 'HQ',
                ],
                [
                    'name'      => 'Headquarters',
                    'phone'     => '+1234567890',
                    'email'     => 'hq@primary.localhost',
                    'address'   => '100 Main Street, Suite 100',
                    'is_active' => true,
                ]
            );

            // 3. Create First Administrator User
            Users::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'email'     => 'admin@primary.localhost',
                ],
                [
                    'site_id'           => $site->id,
                    'firstName'         => 'System',
                    'lastName'          => 'Admin',
                    'password'          => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'role_id'           => 'super-admin',
                ]
            );
        });
    }
}