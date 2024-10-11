<?php

namespace EscolaLms\Jitsi\Dto;

use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class RecordedVideoDto implements DtoContract, InstantiateFromRequest
{
    private string $eventType;
    private string $timestamp;
    private string $sessionId;
    private string $fqn;
    private string $appId;
    private RecordedVideoDataDto $data;

    public function __construct(string $eventType, string $timestamp, string $sessionId, string $fqn, string $appId, array $data)
    {
        $this->eventType = $eventType;
        $this->timestamp = $timestamp;
        $this->sessionId = $sessionId;
        $this->fqn = $fqn;
        $this->appId = $appId;
        $this->data = new RecordedVideoDataDto(...$data);
    }

    public function toArray(): array
    {
        return [];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new static(
            $request->input('eventType'),
            $request->input('timestamp'),
            $request->input('sessionId'),
            $request->input('fqn'),
            $request->input('appId'),
            $request->input('data'),
        );
    }
    public function getFqn(): string
    {
        return $this->fqn;
    }

    public function getData(): RecordedVideoDataDto
    {
        return $this->data;
    }
}
