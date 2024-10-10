<?php

namespace EscolaLms\Jitsi\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class RecordedVideoSaveException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: __('Error while saving jitsi recorded video');
        $code = $code ?: 400;
        parent::__construct($message, $code, $previous);
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'data' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage()
            ]
        ], $this->getCode());
    }
}
