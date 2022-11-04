<?php

namespace EscolaLms\Jitsi\Providers;

use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\Settings\Facades\AdministrableConfig;
use Illuminate\Support\ServiceProvider;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    const CONFIG_KEY = 'jitsi';

    public function register()
    {
        if (class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            if (!$this->app->getProviders(EscolaLmsSettingsServiceProvider::class)) {
                $this->app->register(EscolaLmsSettingsServiceProvider::class);
            }

            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.package_status', ['required', 'string', 'in:' . implode(',', PackageStatusEnum::getValues())], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.host', ['required', 'string']);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.app_id', ['nullable', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.secret', ['nullable', 'string'], false);

            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.jaas_package_status', ['required', 'string', 'in:' . implode(',', PackageStatusEnum::getValues())], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.jaas_host', ['required', 'string']);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.aud', ['required', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.iss', ['required', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.kid', ['required', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.private_key', ['required', 'string'], false);
        }
    }
}
