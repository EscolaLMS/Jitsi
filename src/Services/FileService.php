<?php

namespace EscolaLms\Jitsi\Services;

class FileService
{
    public function getFileFromUrl(string $url): bool|string
    {
        return file_get_contents($url);
    }
}
