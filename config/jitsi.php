<?php

use EscolaLms\Jitsi\Enum\PackageStatusEnum;

return [

    'jitsi_host' => env('JITSI_HOST', 'meet-stage.escolalms.com'),
    'app_id' => env('JITSI_APP_ID', 'meet-id'),
    'secret' => env('JITSI_APP_SECRET', 'Test'),

    'package_status' => PackageStatusEnum::ENABLED,

];
