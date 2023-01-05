<?php

namespace EscolaLms\Jitsi\Services;

use EscolaLms\Auth\Models\User;
use EscolaLms\Jitsi\Enum\JitsiEnum;
use EscolaLms\Jitsi\Enum\PackageStatusEnum;
use EscolaLms\Jitsi\Helpers\StrategyHelper;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use Gnello\Mattermost\Driver;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JitsiService implements JitsiServiceContract
{
    public Driver $driver;
    private array $config;
    private string $mode;

    public function __construct()
    {
        $this->mode = $this->getMode();
        $this->config = config(JitsiEnum::DEFAULT_CONFIG);
    }

    public function generateJwt(
        User $user,
        string $room = '*',
        bool $isModerator = false,
        int $expireInMinutes = 60
    ): string {
        if (!$this->mode) {

            return '';
        }
        $user_data = $this->getUserData($user, $isModerator);
        $payload = [
            'iss' => $this->config['app_id'],
            'aud' => $this->config['app_id'],
            'sub' => $this->config[$this->mode . '_host'],
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
    public function getChannelData(
        User $user,
        string $channelDisplayName,
        bool $isModerator = false,
        array $configOverwrite = [],
        $interfaceConfigOverwrite = []
    ): array {
        if (
            isset($this->config['package_status']) &&
            (string)$this->config['package_status'] !== PackageStatusEnum::ENABLED
        ) {

            return ['error' => 'Package is disabled'];
        }
        $channelName = $this->getChannelSlug($channelDisplayName);
        $data = [
            "domain" => $this->config[$this->mode . '_host'],
            "app_id" => $this->config['app_id'],
            "roomName" => $channelName,
            "configOverwrite" => $configOverwrite,
            "interfaceConfigOverwrite" => $interfaceConfigOverwrite,
            "userInfo" =>  [
                'displayName' => "{$user->first_name} {$user->last_name}",
                'email' => $user->email,
            ]
        ];
        $jwt = '';
        $url = '';
        if ($this->mode) {
            $className = ucfirst($this->mode) .
                'VideoConferenceModeStrategy';
            $jwt = StrategyHelper::useStrategyPattern(
                $className,
                'VideoConferenceModeStrategy',
                'generateJwt',
                $user,
                $channelName,
                $isModerator
            );
            $url = StrategyHelper::useStrategyPattern(
                $className,
                'VideoConferenceModeStrategy',
                'getUrl',
                $jwt,
                $channelName
            );
        }
        if (!empty($jwt)) {
            $data['jwt'] = $jwt;
        }

        return [
            'data' => $data,
            'domain' => $this->config[$this->mode . '_host'],
            'url' => $url ?: "https://" .
                $this->config[$this->mode . '_host'] .
                "/" .
                $channelName .
                (!empty($jwt)  ? "?jwt=" . $jwt : ""),
        ];
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    protected function getUserData($user, $isModerator = false): array
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

    /**
     * Mode priorities Jaas -> self hosted(jitsi) -> meet.jitsy -> disabled
     * @return string
     */
    private function getMode(): string
    {
        $jaasKeys = collect(['app_id', 'jaas_host', 'aud', 'iss', 'kid', 'private_key']);
        $jaasConfigUse = true;
        $jaasKeys->each(function (string $key) use (&$jaasConfigUse) {
            if (!config('jitsi.' . $key)) $jaasConfigUse = false;
        });
        if ($jaasConfigUse) {

            return 'jaas';
        }
        $jitsiKeys = collect(['jitsi_host', 'app_id', 'secret']);
        $jitsiConfigUse = true;
        $jitsiKeys->each(function (string $key) use (&$jitsiConfigUse) {
            if (!config('jitsi.' . $key)) $jitsiConfigUse = false;
        });
        if ($jitsiConfigUse) {

            return 'jitsi';
        }
        return '';
    }

    private function getChannelSlug(string $channelName): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', Str::camel($channelName));
    }
}
