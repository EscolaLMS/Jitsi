<?php

use EscolaLms\Jitsi\Enum\PackageStatusEnum;

return [

    'host' => env('JAAS_HOST', 'https://8x8.vc/'),
    'aud' => env('JAAS_AUD', 'jitsi'),
    'iss' => env('JAAS_ISS', 'chat'),
    'sub' => env('JAAS_SUB', ''),
    'kid' => env('JAAS_KEY_ID', ''),
    'private_key' => env('JAAS_PRIVATE_KEY', ''),

    'package_status' => PackageStatusEnum::ENABLED,

];
