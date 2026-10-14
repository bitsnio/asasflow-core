<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Bitsnio\AsasFlow\Providers\ModuleServiceProvider as BaseModuleServiceProvider;

class CoreServiceProvider extends BaseModuleServiceProvider
{
    protected string $name = 'Core';

    protected string $nameLower = 'core';

    /**
     * Additional service providers required by this module.
     *
     * @var array<class-string>
     */
    protected array $providers = [
        // \Modules\Core\Providers\ExampleServiceProvider::class,
    ];

    /**
     * Artisan commands provided by this module.
     *
     * @var array<class-string<\Illuminate\Console\Command>>
     */
    protected array $commands = [
        // \Modules\Core\Console\ExampleCommand::class,
    ];
}
