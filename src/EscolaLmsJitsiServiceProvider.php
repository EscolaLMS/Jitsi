<?php

namespace EscolaLms\Jitsi;

use Illuminate\Support\ServiceProvider;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use EscolaLms\Jitsi\Services\JitsiService;
use EscolaLms\Settings\Facades\AdministrableConfig;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;

/**
 * SWAGGER_VERSION
 */

class EscolaLmsJitsiServiceProvider extends ServiceProvider
{

    const CONFIG_KEY = 'jitsi';

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
        AdministrableConfig::registerConfig(self::CONFIG_KEY . '.package_status', ['required', 'string', 'in:' . implode(',', PackageStatusEnum::getValues())], false);
        AdministrableConfig::registerConfig(self::CONFIG_KEY . '.host', ['required', 'string'], true);
        AdministrableConfig::registerConfig(self::CONFIG_KEY . '.app_id', ['nullable', 'string'], false);
        AdministrableConfig::registerConfig(self::CONFIG_KEY . '.secret', ['nullable', 'string'], false);
    }


    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/jitsi.php',
            'jitsi'
        );
    }
}
