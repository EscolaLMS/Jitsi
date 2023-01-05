<?php

namespace EscolaLms\Jitsi\Services;

use EscolaLms\Auth\Models\User;
use EscolaLms\Jitsi\Services\Contracts\JaasServiceContract;
use Gnello\Mattermost\Driver;
use Firebase\JWT\JWT;

class JaasService extends JitsiService implements JaasServiceContract
{
    public Driver $driver;
    private array $config;

    public function generateJwt(
        User $user,
        string $room = '*',
        bool $isModerator = false,
        int $expireInMinutes = 60
    ): string {
        $userData = $this->getUserData($user, $isModerator);
        $payload = [
            'aud' => $this->config['aud'],
            'iss' => $this->config['iss'],
            'exp' => now()->addMinutes($expireInMinutes)->timestamp,
            'sub' => $this->config['app_id'],
            'room' => $room,
            'context' => [
                'user' =>  $userData,
            ],
        ];
        return JWT::encode($payload, $this->config['private_key'], 'RS256', $this->config['kid']);
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }
}
