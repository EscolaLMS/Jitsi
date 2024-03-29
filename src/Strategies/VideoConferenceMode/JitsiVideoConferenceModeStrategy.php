<?php

namespace EscolaLms\Jitsi\Strategies\VideoConferenceMode;

use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;
use EscolaLms\Jitsi\Strategies\Contracts\VideoConferenceModeStrategyContract;

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
        if (isset($data[0]) && $this->shouldGenerateJWT()) {
            $this->jitsiService->setConfig($this->config);
            return $this->jitsiService->generateJwt(
                $data[0],
                $data[1] ?? '*',
                $data[2] ?? false,
                $data[3] ?? 60
            );
        }
        return null;
    }

    public function getUrl(array $data): string
    {
        $jwt = $data[0] ?? '';
        $channelName = $data[1] ?? '';

        return 'https://' .
        $this->config['jitsi_host'] .
        '/' .
        $channelName .
        (!empty($jwt)  ? "?jwt=" . $jwt : "");
    }

    private function shouldGenerateJWT(): bool
    {
        return !(!$this->config["app_id"] && !$this->config["secret"]);
    }
}
