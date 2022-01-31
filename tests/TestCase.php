<?php

namespace EscolaLms\Jitsi\Tests;



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
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);

        $app['config']->set('jitsi.app_id', 'app_id');
        $app['config']->set('jitsi.secret', 'secret');
        $app['config']->set('jitsi.host', 'localhost');
    }
}
