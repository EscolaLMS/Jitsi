<?php

namespace EscolaLms\Jitsi;

use EscolaLms\Jitsi\Providers\SettingsServiceProvider;
use EscolaLms\Jitsi\Services\Contracts\JaasServiceContract;
use EscolaLms\Jitsi\Services\JaasService;
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
        JaasServiceContract::class => JaasService::class,
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
        $this->mergeConfigFrom(
            __DIR__ . '/../config/jaas.php',
            'jaas'
        );

        $this->app->register(SettingsServiceProvider::class);
    }
}
