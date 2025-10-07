<?php

/**
 * This is the core configuration file for the Laravel CMS Core package.
 * It contains various settings that control the behavior and features of the package.
 */

return [
    'APP_VERSION' => env('APP_VERSION', '1.0.0'), // @phpstan-ignore-line

    /**
     * GOOGLE APIS
     */
    'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY'), // @phpstan-ignore-line
    'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY'), // @phpstan-ignore-line
    'GOOGLE_MAPS_API_KEY' => env('GOOGLE_MAPS_API_KEY'), // @phpstan-ignore-line
    'GOOGLE_TAG_MANAGER_ID' => env('GOOGLE_TAG_MANAGER_ID'), // @phpstan-ignore-line

    /**
     * SERVICE API
     */
    'SERVICE_API_URL' => env('SERVICE_API_URL', 'https://toolbox.leobsst.fr'), // @phpstan-ignore-line
    'SERVICE_API_KEY' => env('SERVICE_API_KEY'), // @phpstan-ignore-line
    'SERVICE_API_SECRET' => env('SERVICE_API_SECRET'), // @phpstan-ignore-line

    /**
     * DEFAULT VIEWS
     */
    'features' => [
        /**
         * DEFAULT PAGES VIEWS
         */
        'pages' => [
            'router_view' => 'laravel-cms-core::livewire.page.show',
            'content_view' => 'laravel-cms-core::livewire.page.partials.content',
            'contact_view' => 'laravel-cms-core::livewire.page.partials.contact',
            'components' => [
                'gallery' => 'laravel-cms-core::components.features.pages.gallery.page-gallery-component',
            ],
        ],

        /**
         * DEFAULT DISKS FOR FILE EXPLORER
         */
        'file_explorer' => [
            'disks' => [
                'public',
                'uploads',
            ],
        ],
    ],
];
