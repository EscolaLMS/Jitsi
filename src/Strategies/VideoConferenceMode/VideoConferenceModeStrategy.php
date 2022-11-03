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

    public function generateJwt($a): ?string
    {
        return $this->videoConferenceModeStrategy->generateJwt($a);
    }
}
