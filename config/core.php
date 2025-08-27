<?php

/**
 * This is the core configuration file for the Laravel CMS Core package.
 * It contains various settings that control the behavior and features of the package.
 */

return [
    'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY'),
    'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY'),
    'GOOGLE_MAPS_API_KEY' => env('GOOGLE_MAPS_API_KEY'),
];
