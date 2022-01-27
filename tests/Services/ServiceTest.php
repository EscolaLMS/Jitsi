<?php

namespace EscolaLms\Mattermost\Tests\Services;

use BadMethodCallException;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Mattermost\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use EscolaLms\Mattermost\Services\Contracts\MattermostServiceContract;
use EscolaLms\Mattermost\Services\MattermostService;

class ServiceTest extends TestCase
{

    use CreatesUsers;

    private MattermostServiceContract $service;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->makeStudent();
        $this->service = $this->app->make(MattermostServiceContract::class);
        $this->mock->reset();
    }

    public function testAddUser()
    {
        $this->mock->append(new Response(200, ['Token' => 'Token'], 'Hello, World'));
        $this->assertTrue($this->service->addUser($this->user));
    }

    public function testAddUserToTeam()
    {
        $this->mock->append(new Response(200, ['Token' => 'Token'], json_encode(["id" => 123])));
        $this->mock->append(new Response(200, ['Token' => 'Token'], json_encode(["id" => 123])));
        $this->mock->append(new Response(200, ['Token' => 'Token'], ""));

        $this->assertTrue($this->service->addUserToTeam($this->user, "Courses"));
    }

    public function testAddUserToChannel()
    {
        $this->mock->append(new Response(200, ['Token' => 'Token'], json_encode(["id" => 123])));
        $this->mock->append(new Response(200, ['Token' => 'Token'], json_encode(["id" => 123])));
        $this->mock->append(new Response(200, ['Token' => 'Token'], json_encode(["id" => 123])));
        $this->mock->append(new Response(200, ['Token' => 'Token'], ""));
        $this->mock->append(new Response(200, ['Token' => 'Token'], ""));
        $this->mock->append(new Response(200, ['Token' => 'Token'], ""));

        $this->assertTrue($this->service->addUserToChannel($this->user, "Courses"));
    }

    public function testGetOrCreateTeam()
    {
        $response = new Response(200, ['Token' => 'Token'], json_encode(["id" => 123]));
        $this->mock->append($response);

        $this->assertEquals($this->service->getOrCreateTeam("Team name"),  $response);
    }

    public function testGetOrCreateChannel()
    {
        $response = new Response(200, ['Token' => 'Token'], json_encode(["id" => 123]));
        $this->mock->append($response);
        $this->mock->append($response);

        $this->assertEquals($this->service->getOrCreateChannel("Team name", "channel name"),  $response);
    }

    public function testGetOrCreateUser()
    {
        $response = new Response(200, ['Token' => 'Token'], json_encode(["id" => 123]));
        $this->mock->append($response);

        $this->assertEquals($this->service->getOrCreateUser($this->user),  $response);
    }

    public function testSendMessage()
    {
        $response = new Response(200, ['Token' => 'Token'], json_encode(["id" => 123]));
        $this->mock->append($response);
        $this->mock->append($response);

        $this->assertTrue($this->service->sendMessage("hello world", "Town Square"));
    }

    public function testGenerateUserCredentials()
    {
        $object = ["id" => 123];
        $response = new Response(200, ['Token' => 'Token'], json_encode($object));
        $this->mock->append($response);
        $this->mock->append($response);

        $response = $this->service->generateUserCredentials($this->user);

        $this->assertEquals((array) $response['status'], $object);
        $this->assertEquals((array) $response['user'], $object);
        $this->assertNotNull($response['password']);
    }
}
