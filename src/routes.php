<?php

use EscolaLms\Jitsi\Http\Controllers\JitsiApiController;
use Illuminate\Support\Facades\Route;

Route::post('api/jitsi/recorded-video', [JitsiApiController::class, 'recordedVideo']);
