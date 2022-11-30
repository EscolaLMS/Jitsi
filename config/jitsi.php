<?php

use EscolaLms\Jitsi\Enum\PackageStatusEnum;

return [

    'jitsi_host' => env('JITSI_HOST', 'meet-stage.escolalms.com'),
    'app_id' => env('JITSI_APP_ID', 'meet-id'),
    'secret' => env('JITSI_APP_SECRET', 'Test'),

    'package_status' => PackageStatusEnum::ENABLED,

    'jaas_host' => env('JAAS_HOST', 'https://8x8.vc/'),
    'aud' => env('JAAS_AUD', 'jitsi'),
    'iss' => env('JAAS_ISS', 'chat'),
    'sub' => env('JAAS_SUB', ''),
    'kid' => env('JAAS_KEY_ID', ''),
    'private_key' => env('JAAS_PRIVATE_KEY', '')

];
