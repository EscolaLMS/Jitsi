<?php

namespace EscolaLms\Jitsi\Dto;

class RecordedVideoDataDto
{
    private array $participants;
    private string $initiatorId;
    private $durationSec;
    private string $startTimestamp;
    private string $endTimestamp;
    private string $recordingSessionId;
    private string $preAuthenticatedLink;
    private bool|null $share;

    public function __construct(array $participants, string $initiatorId, int $durationSec, string $startTimestamp, string $endTimestamp, string $recordingSessionId, string $preAuthenticatedLink, bool|null $share)
    {
        $this->participants = $participants;
        $this->initiatorId = $initiatorId;
        $this->durationSec = $durationSec;
        $this->startTimestamp = $startTimestamp;
        $this->endTimestamp = $endTimestamp;
        $this->recordingSessionId = $recordingSessionId;
        $this->preAuthenticatedLink = $preAuthenticatedLink;
        $this->share = $share;
    }

    public function getPreAuthenticatedLink(): string
    {
        return $this->preAuthenticatedLink;
    }

    public function getStartTimestamp(): string
    {
        return $this->startTimestamp;
    }
}
