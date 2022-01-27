<?php

/**
 * This Driver is a Laravel integration for the package php-mattermost-driver
 * (https://github.com/gnello/php-mattermost-driver)
 *
 * For the full copyright and license information, please read the LICENSE.txt
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/gnello/laravel-mattermost-driver/contributors
 *
 * God bless this mess too.
 *
 * @author Luca Agnello <luca@gnello.com>
 * @link https://api.mattermost.com/
 */

return [

    'host' => env('JITSI_HOST', 'localhost'),
    'app_id' => env('JITSI_APP_ID', 'admin'),
    'secret' => env('JITSI_APP_SECRET', 'secret'),

    'package_status' => PackageStatusEnum::ENABLED,

];
