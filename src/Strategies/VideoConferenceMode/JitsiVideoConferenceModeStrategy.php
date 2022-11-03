<?php

namespace EscolaLms\Jitsi\Strategies\VideoConferenceMode;

use EscolaLms\Auth\Models\User;
use EscolaLms\Jitsi\Helpers\StrategyHelper;
use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use EscolaLms\Jitsi\Strategies\Contracts\VideoConferenceModeStrategyContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JitsiVideoConferenceModeStrategy implements VideoConferenceModeStrategyContract
{
    private array $config;
    private JitsiServiceContract $jitsiService;

    public function __construct()
    {
        $this->config = config('jitsi');
        $this->jitsiService = app(JitsiServiceContract::class);
    }

    public function generateJwt(array $data): ?string
    {
        if (count($data) >= 3 && $this->shouldGenerateJWT()) {
            $this->jitsiService->setConfig($this->config);
            return $this->jitsiService->generateJwt($data[0], $data[1], $data[2], $data[3] ?? 60);
        }
        return null;
    }

    private function shouldGenerateJWT(): bool
    {
        return !(!$this->config["app_id"] && !$this->config["secret"]);
    }
}
