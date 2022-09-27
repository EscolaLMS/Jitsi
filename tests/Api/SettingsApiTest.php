<?php

namespace EscolaLms\Jitsi\Tests\Api;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\Jitsi\EscolaLmsJitsiServiceProvider;
use EscolaLms\Jitsi\Providers\SettingsServiceProvider;
use EscolaLms\Jitsi\Tests\TestCase;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class SettingsApiTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            $this->markTestSkipped('Settings package not installed');
        }

        $this->seed(PermissionTableSeeder::class);

        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('admin');
    }

    public function testAdministrableConfigApi(): void
    {
        $configKey = SettingsServiceProvider::CONFIG_KEY;

        $packageStatus = $this->faker->randomElement(PackageStatusEnum::getValues());
        $host = $this->faker->domainWord;
        $appId = $this->faker->uuid;
        $secret = $this->faker->uuid;

        $this->response = $this->actingAs($this->user, 'api')->json(
            'POST',
            '/api/admin/config',
            [
                'config' => [
                    [
                        'key' => "$configKey.package_status",
                        'value' => $packageStatus,
                    ],
                    [
                        'key' => "$configKey.host",
                        'value' => $host,
                    ],
                    [
                        'key' => "$configKey.app_id",
                        'value' => $appId,
                    ],
                    [
                        'key' => "$configKey.secret",
                        'value' => $secret,
                    ],
                ]
            ]
        );
        $this->response->assertOk();

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/config'
        );

        $this->response->assertOk();

        $this->response->assertJsonFragment([
            $configKey => [
                'package_status' => [
                    'full_key' => "$configKey.package_status",
                    'key' => 'package_status',
                    'rules' => [
                        'required',
                        'string',
                        'in:' . implode(',', PackageStatusEnum::getValues()),
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $packageStatus,
                ],
                'host' => [
                    'full_key' => "$configKey.host",
                    'key' => 'host',
                    'rules' => [
                        'required',
                        'string'
                    ],
                    'public' => true,
                    'readonly' => false,
                    'value' => $host,
                ],
                'app_id' => [
                    'full_key' => "$configKey.app_id",
                    'key' => 'app_id',
                    'rules' => [
                        'nullable',
                        'string',
                    ],
                    'public' => false,
                    'value' => $appId,
                    'readonly' => false,
                ],
                'secret' => [
                    'full_key' => "$configKey.secret",
                    'key' => 'secret',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $secret,
                ],
            ],
        ]);

        $this->response = $this->json(
            'GET',
            '/api/config'
        );

        $this->response->assertOk();

        $this->response->assertJsonFragment([
            $configKey => [
                'host' => $host,
            ],
        ]);
    }
}