<?php

namespace EscolaLms\Jitsi\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Jitsi\Dto\RecordedVideoDto;
use EscolaLms\Jitsi\Http\Requests\RecordedVideoRequest;
use EscolaLms\Jitsi\Services\Contracts\JitsiVideoServiceContract;
use Illuminate\Http\JsonResponse;

class JitsiApiController extends EscolaLmsBaseController
{
    public function __construct(
        private JitsiVideoServiceContract $jitsiVideoService,
    ) {}

    public function recordedVideo(RecordedVideoRequest $request): JsonResponse
    {
        $this->jitsiVideoService->recordedVideo(RecordedVideoDto::instantiateFromRequest($request));
        return $this->sendSuccess(__('Screen saved successfully'));
    }
}
