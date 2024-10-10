<?php

namespace EscolaLms\Jitsi\Strategies\VideoConferenceMode;

use EscolaLms\Jitsi\Services\Contracts\JaasServiceContract;
use EscolaLms\Jitsi\Strategies\Contracts\VideoConferenceModeStrategyContract;

class JaasVideoConferenceModeStrategy implements VideoConferenceModeStrategyContract
{
    private array $config;
    private JaasServiceContract $jaasService;

    public function __construct()
    {
        $this->config = config('jitsi');
        $this->jaasService = app(JaasServiceContract::class);
    }

    public function generateJwt(array $data): ?string
    {
        if (isset($data[0]) && $this->shouldGenerateJWT()) {
            $this->jaasService->setConfig($this->config);
            return $this->jaasService->generateJwt(
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

        $sub = strlen($this->config['sub']) > 0 ? $this->config['sub'] . '/' : '';
        return 'https://' .
        $this->config['jaas_host'] .
        '/' .
        $sub .
        $channelName .
        (!empty($jwt)  ? "?jwt=" . $jwt : "");
    }

    private function shouldGenerateJWT(): bool
    {
        return !(
            !$this->config['jaas_host'] &&
            !$this->config['private_key'] &&
            !$this->config['kid'] &&
            !$this->config['iss'] &&
            !$this->config['aud'] &&
            !$this->config['sub']
        );
    }
}
