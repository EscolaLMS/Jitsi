<?php

namespace EscolaLms\Jitsi\Strategies\Contracts;

interface VideoConferenceModeStrategyContract
{
    public function generateJwt(array $data): ?string;
}