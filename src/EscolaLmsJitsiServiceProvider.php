<?php

namespace EscolaLms\Jitsi;

use EscolaLms\Jitsi\Providers\SettingsServiceProvider;
use EscolaLms\Jitsi\Services\Contracts\JaasServiceContract;
use EscolaLms\Jitsi\Services\Contracts\JitsiVideoServiceContract;
use EscolaLms\Jitsi\Services\JaasService;
use EscolaLms\Jitsi\Services\JitsiVideoService;
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
        JitsiVideoServiceContract::class => JitsiVideoService::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__ . '/../config/jitsi.php' => config_path('jitsi.php'),
        ], 'jitsi.config');

        $this->publishes([
            __DIR__ . '/../config/jaas.php' => config_path('jaas.php'),
        ], 'jaas.config');
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
