<?php

namespace EscolaLms\Jitsi\Services;

use EscolaLms\Auth\Models\User;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use Gnello\Mattermost\Driver;
use Gnello\Mattermost\Laravel\Facades\Mattermost;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Firebase\JWT\JWT;


class JitsiService implements JitsiServiceContract
{
    public Driver $driver;


    private function shouldGenerateJWT(): bool
    {
        return true;
    }

    public function __construct()
    {
    }

    public function getChannelData(User $user, string $channelName, bool $isModerator = false): array
    {
        // check 
        return [];
    }


    private function getChannelSlug(string $channelName): string
    {
        return Str::slug($channelName);
    }
}
