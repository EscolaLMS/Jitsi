<?php

namespace EscolaLms\Jitsi\Tests;



use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\ModelFields\ModelFieldsServiceProvider;
use EscolaLms\Core\EscolaLmsServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use EscolaLms\Jitsi\EscolaLmsJitsiServiceProvider;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;

use Laravel\Passport\Passport;
use EscolaLms\Lrs\Tests\Models\Client;
use EscolaLms\Auth\Models\User;

use EscolaLms\Core\Tests\TestCase as CoreTestCase;

// use GuzzleHttp\Client;


class TestCase extends CoreTestCase
{
    use DatabaseTransactions;


    protected function setUp(): void
    {
        parent::setUp();
        Passport::useClientModel(Client::class);
    }

    protected function getPackageProviders($app): array
    {

        return [
            ...parent::getPackageProviders($app),
            EscolaLmsJitsiServiceProvider::class,
            EscolaLmsSettingsServiceProvider::class,
            EscolaLmsAuthServiceProvider::class,
            ModelFieldsServiceProvider::class,
            EscolaLmsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);

        $app['config']->set('jitsi.app_id', 'app_id');
        $app['config']->set('jitsi.secret', 'secret');
        $app['config']->set('jitsi.jitsi_host', 'localhost');
        $app['config']->set('jitsi.package_status', PackageStatusEnum::ENABLED);

        $app['config']->set('jitsi.jaas_host', 'localhost');
        $app['config']->set('jitsi.aud', 'jitsi');
        $app['config']->set('jitsi.iss', 'chat');
        $app['config']->set('jitsi.sub', '');
        $app['config']->set('jitsi.kid', '');
    }
}
