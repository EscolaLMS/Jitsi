<?php

namespace EscolaLms\Jitsi\Tests\Api;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\Jitsi\Providers\SettingsServiceProvider;
use EscolaLms\Jitsi\Tests\TestCase;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

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
        Config::set('escola_settings.use_database', true);

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
        $aud = $this->faker->word;
        $iss = $this->faker->word;
        $kid = $this->faker->word;
        $privateKey = $this->faker->word;

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
                        'key' => "$configKey.jitsi_host",
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
                    [
                        'key' => "$configKey.jaas_host",
                        'value' => $host,
                    ],
                    [
                        'key' => "$configKey.aud",
                        'value' => $aud,
                    ],
                    [
                        'key' => "$configKey.iss",
                        'value' => $iss,
                    ],
                    [
                        'key' => "$configKey.kid",
                        'value' => $kid,
                    ],
                    [
                        'key' => "$configKey.private_key",
                        'value' => $privateKey,
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
                        'nullable',
                        'string',
                        'in:' . implode(',', PackageStatusEnum::getValues()),
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $packageStatus,
                ],
                'jitsi_host' => [
                    'full_key' => "$configKey.jitsi_host",
                    'key' => 'jitsi_host',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => true,
                    'readonly' => false,
                    'value' => $host,
                ],
                'jaas_host' => [
                    'full_key' => "$configKey.jaas_host",
                    'key' => 'jaas_host',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => true,
                    'readonly' => false,
                    'value' => $host,
                ],
                'aud' => [
                    'full_key' => "$configKey.aud",
                    'key' => 'aud',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $aud,
                ],
                'kid' => [
                    'full_key' => "$configKey.kid",
                    'key' => 'kid',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $kid,
                ],
                'iss' => [
                    'full_key' => "$configKey.iss",
                    'key' => 'iss',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $iss,
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
                'private_key' => [
                    'full_key' => "$configKey.private_key",
                    'key' => 'private_key',
                    'rules' => [
                        'nullable',
                        'string'
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $privateKey,
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
                'jitsi_host' => $host,
                'jaas_host' => $host,
            ],
        ]);
    }
}
