<?php

namespace EscolaLms\Jitsi\Services\Contracts;

use EscolaLms\Jitsi\Dto\RecordedVideoDto;

interface JitsiVideoServiceContract
{
    public function recordedVideo(RecordedVideoDto $dto): void;
}
