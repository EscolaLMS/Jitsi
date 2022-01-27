<?php

namespace EscolaLms\Mattermost\Tests;



use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use EscolaLms\Mattermost\EscolaLmsMattermostServiceProvider;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use Gnello\Mattermost\Laravel\MattermostServiceProvider;

use EscolaLms\Lrs\Database\Seeders\LrsSeeder;
use Laravel\Passport\Passport;
use EscolaLms\Lrs\Tests\Models\Client;
use EscolaLms\Auth\Models\User;

use EscolaLms\Core\Tests\TestCase as CoreTestCase;
// use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class TestCase extends CoreTestCase
{
    use DatabaseTransactions;

    protected MockHandler $mock;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::useClientModel(Client::class);
    }

    protected function getPackageProviders($app): array
    {

        return [
            ...parent::getPackageProviders($app),
            EscolaLmsMattermostServiceProvider::class,
            EscolaLmsSettingsServiceProvider::class,
            MattermostServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);

        $this->mock = new MockHandler([new Response(200, ['Token' => 'Token'], 'Hello, World'),]);

        $handlerStack = HandlerStack::create($this->mock);

        $app['config']->set('mattermost.servers.default.guzzle', ['handler' => $handlerStack]);
    }
}
