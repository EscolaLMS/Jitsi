<?php

use EscolaLms\Jitsi\Enum\PackageStatusEnum;

return [

    'host' => env('JITSI_HOST', 'meet-stage.escolalms.com'),
    'app_id' => env('JITSI_APP_ID', 'meet-id'),
    'secret' => env('JITSI_APP_SECRET', 'ZKGfn5kYsv47avM4'),

    'package_status' => PackageStatusEnum::ENABLED,

];
