<?php

namespace EscolaLms\Jitsi;

use EscolaLms\Jitsi\Providers\SettingsServiceProvider;
use Illuminate\Support\ServiceProvider;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use EscolaLms\Jitsi\Services\JitsiService;

/**
 * SWAGGER_VERSION
 */

class EscolaLmsJitsiServiceProvider extends ServiceProvider
{
    public $singletons = [
        JitsiServiceContract::class => JitsiService::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/jitsi.php',
            'jitsi'
        );

        $this->app->register(SettingsServiceProvider::class);
    }
}
