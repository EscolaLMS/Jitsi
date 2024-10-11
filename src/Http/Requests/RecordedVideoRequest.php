<?php

namespace EscolaLms\Jitsi\Http\Requests;

use EscolaLms\Jitsi\Enum\JitsiEventsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordedVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'eventType' => ['required', 'string', Rule::in(JitsiEventsEnum::RECORDING_UPLOADED)],
            'timestamp' => ['required', 'numeric'],
            'sessionId' => ['required', 'string'],
            'fqn' => ['required', 'string'],
            'appId' => ['required', 'string', Rule::in([config('jitsi.app_id')])],
            'data' => ['required', 'array'],
            'data.participants' => ['required', 'array'],
            'data.share' => ['nullable', 'boolean'],
            'data.initiatorId' => ['required', 'string'],
            'data.durationSec' => ['required', 'numeric'],
            'data.startTimestamp' => ['required', 'numeric'],
            'data.endTimestamp' => ['required', 'numeric'],
            'data.recordingSessionId' => ['required', 'string'],
            'data.preAuthenticatedLink' => ['required', 'string'],
        ];
    }
}
