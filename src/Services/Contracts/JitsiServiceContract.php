<?php

namespace EscolaLms\Jitsi\Services\Contracts;

use EscolaLms\Auth\Models\User;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Auth;


interface JitsiServiceContract
{
    public function getChannelData(User $user, string $channelDisplayName, bool $isModerator = false, array $configOverwrite = [], $interfaceConfigOverwrite = []): array;
}
