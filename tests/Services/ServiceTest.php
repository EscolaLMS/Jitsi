<?php

namespace EscolaLms\Jitsi\Tests\Services;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Jitsi\Enum\JitsiEnum;
use EscolaLms\Jitsi\Helpers\StringHelper;
use EscolaLms\Jitsi\Services\Contracts\JaasServiceContract;
use EscolaLms\Jitsi\Tests\TestCase;
use EscolaLms\Jitsi\Facades\Jitsi;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\WithFaker;

class ServiceTest extends TestCase
{

    use CreatesUsers, WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->makeStudent();
    }

    private function decodeJWT($token)
    {
        return (json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1])))));
    }

    public function testServiceWithJwtJitsi()
    {
        // public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array
        putenv('VIDEO_CONFERENCE_MODE=jitsi');
        $config = config(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE));
        $data = Jitsi::getChannelData($this->user, $this->faker->text(15));
        $jwt = $this->decodeJWT($data['data']['jwt']);

        $this->assertEquals($data['data']['domain'], $config['host']);
        $this->assertEquals($data['data']['userInfo']['email'], $this->user->email);
        $this->assertEquals($jwt->user->email, $this->user->email);
        $this->assertEquals($jwt->user->moderator, false);
    }

    public function testServiceWithJwtJaas()
    {
        // public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array

        putenv('VIDEO_CONFERENCE_MODE=jaas');
        $private_key = openssl_pkey_new([
            'digest_alg' => 'RS256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);
        \Config::set(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE) . '.private_key', $private_key);
        $config = config(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE));
        $data = Jitsi::getChannelData($this->user, $this->faker->text(15));
        $jwt = $this->decodeJWT($data['data']['jwt']);
        $this->assertEquals($data['data']['domain'], $config['host']);
        $this->assertEquals($data['data']['userInfo']['email'], $this->user->email);
        $this->assertEquals($jwt->context->user->email, $this->user->email);
        $this->assertEquals($jwt->context->user->moderator, false);
    }

    public function testServiceWithJwtAndSettingsJaas()
    {
        // public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array
        putenv('VIDEO_CONFERENCE_MODE=jaas');
        $config = config(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE));
        $private_key = openssl_pkey_new([
            'digest_alg' => 'RS256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);
        \Config::set(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE) . '.private_key', $private_key);
        $data = Jitsi::getChannelData($this->user, "Test Channel Name", true, ['foo' => 'bar'], ['bar' => 'foo']);
        $jwt = $this->decodeJWT($data['data']['jwt']);
        $this->assertEquals($data['data']['domain'], $config['host']);
        $this->assertEquals($data['data']['userInfo']['email'], $this->user->email);
        $this->assertEquals($jwt->context->user->email, $this->user->email);
        $this->assertEquals($jwt->context->user->moderator, true);
        $this->assertEquals($data['data']['configOverwrite'], ["foo" => "bar"]);
        $this->assertEquals($data['data']['interfaceConfigOverwrite'], ["bar" => "foo"]);
    }

    public function testServiceWithJwtAndSettingsJitsi()
    {
        // public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array

        putenv('VIDEO_CONFERENCE_MODE=jitsi');
        $config = config(env('VIDEO_CONFERENCE_MODE', JitsiEnum::DEFAULT_MODE));
        $data = Jitsi::getChannelData($this->user, "Test Channel Name", true, ['foo' => 'bar'], ['bar' => 'foo']);

        $jwt = $this->decodeJWT($data['data']['jwt']);

        $this->assertEquals($data['data']['domain'], $config['host']);
        $this->assertEquals($data['data']['userInfo']['email'], $this->user->email);
        $this->assertEquals($jwt->user->email, $this->user->email);
        $this->assertEquals($jwt->user->moderator, true);
        $this->assertEquals($data['data']['configOverwrite'], ["foo" => "bar"]);
        $this->assertEquals($data['data']['interfaceConfigOverwrite'], ["bar" => "foo"]);
    }

    public function testDisabledServiceWithJwt()
    {
        // public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array

        //$config = config("jitsi");

        config(['jitsi.package_status' => PackageStatusEnum::DISABLED]);


        $data = Jitsi::getChannelData($this->user, "Test Channel Name");
        $this->assertTrue(isset($data['error']));
    }

    public function testGenerateSlugForJitsi()
    {
        $slug = 'Test Słowny 123 Śękowy';
        $convertSlug = StringHelper::convertToJitsiSlug($slug);
        $this->assertTrue($slug !== $convertSlug);
        $this->assertTrue('testslowny123sekowy' === $convertSlug);
    }
}
