<?php

namespace EscolaLms\Jitsi\Strategies\VideoConferenceMode;

use EscolaLms\Jitsi\Strategies\Contracts\VideoConferenceModeStrategyContract;

class VideoConferenceModeStrategy
{
    private VideoConferenceModeStrategyContract $videoConferenceModeStrategy;

    public function __construct(
        VideoConferenceModeStrategyContract $videoConferenceModeStrategy
    )
    {
        $this->videoConferenceModeStrategy = $videoConferenceModeStrategy;
    }

    public function generateJwt(array $data): ?string
    {
        return $this->videoConferenceModeStrategy->generateJwt($data);
    }

    public function getUrl(array $data): ?string
    {
        return $this->videoConferenceModeStrategy->getUrl($data);
    }
}
