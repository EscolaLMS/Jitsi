<?php

namespace EscolaLms\Jitsi\Services;

use EscolaLms\Auth\Models\User;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use Gnello\Mattermost\Driver;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class JitsiService implements JitsiServiceContract
{
    public Driver $driver;
    private array $config;

    public function __construct()
    {
        $this->config = config("jitsi");
    }

    private function shouldGenerateJWT(): bool
    {
        return !(!$this->config["app_id"] && !$this->config["secret"]);
    }

    private function getUserData($user, $isModerator = false): array
    {
        $user_data = [
            'id' => $user->id,
            'name' => "{$user->first_name} {$user->last_name}",
            'displayName' => "{$user->first_name} {$user->last_name}",
            'email' => $user->email,
            "moderator" => $isModerator
        ];

        if (!empty($user->avatar_path)) {
            $user_data['avatar'] = Storage::url($user->avatar_path);
        }

        return $user_data;
    }

    private function generateJwt($user, $room = '*', $isModerator = false, $expireInMinutes = 60): string
    {

        $user_data = $this->getUserData($user, $isModerator);
        $payload = [
            'iss' => $this->config['app_id'],
            'aud' => $this->config['app_id'],
            'sub' => $this->config['host'],
            'exp' => now()->addMinutes($expireInMinutes)->timestamp,
            'room' => $room,
            'user' =>  $user_data,
        ];
        return JWT::encode($payload, $this->config['secret'], 'HS256');
    }

    /**
     * Generates data to pass for Jitsi player
     *
     * @param \EscolaLms\Auth\Models\User $user
     * @param string $channelDisplayName name of the channel, will be converted with cammelCase
     * @param bool $isModerator, is this user moderator
     * @param array $configOverwrite, https://github.com/jitsi/jitsi-meet/blob/master/config.js
     * @param array $interfaceConfigOverwrite, https://github.com/jitsi/jitsi-meet/blob/master/interface_config.js
     * @return array ['data' => '...', 'domain' => '...', 'url' => '...']
     * 'data' is user for react component, iframe API https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-web-sdk
     * 'domain' is self explanatory
     * 'url' that you can run in open in new window mode, (not recommended)
     *
     */
    public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array
    {

        if ($this->config['package_status'] != PackageStatusEnum::ENABLED) {
            return ['error' => 'Package is disabled'];
        }

        $channelName = $this->getChannelSlug($channelDisplayName);

        $jwt = $this->shouldGenerateJWT() ? $this->generateJwt($user, $channelName, $isModerator) : null;

        $data = [
            "domain" => $this->config['host'],
            "roomName" => $channelName,
            "configOverwrite" => array_merge([
                /*
                "startWithAudioMuted" => true,
                "disableModeratorIndicator" => true,
                "startScreenSharing" => true,
                "enableEmailInStats" => false,
                */], $configOverwrite),
            "interfaceConfigOverwrite" => array_merge([
                //"DISABLE_JOIN_LEAVE_NOTIFICATIONS" => true,
            ], $interfaceConfigOverwrite),
            "userInfo" =>  [
                'displayName' => "{$user->first_name} {$user->last_name}",
                'email' => $user->email,
            ]
        ];

        if (!empty($jwt)) {
            $data['jwt'] = $jwt;
        }


        return [
            'data' => $data,
            'domain' => $this->config['host'],
            'url' => "https://" . $this->config['host'] . "/" .  $channelName . (!empty($jwt)  ? "?jwt=" . $jwt : ""),
        ];
    }


    private function getChannelSlug(string $channelName): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', Str::camel($channelName));
    }
}
