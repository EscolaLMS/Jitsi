<?php

namespace EscolaLms\Jitsi\Strategies\Contracts;

interface VideoConferenceModeStrategyContract
{
    public function generateJwt(array $data): ?string;
    public function getUrl(array $data): string;
}
