<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 style="text-align: center;">My Laravel CMS core</h1>

<p align="center">
<a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="MIT Licensed"></a>
</p>



## Installation

You can install the package via composer:

``` bash
composer require leobsst/laravel-cms-core
```

You must publish assets via:
``` bash
php artisan vendor:publish --tag=laravel-cms-core-assets
```

(optional) You can publish migrations via:
``` bash
php artisan vendor:publish --tag=laravel-cms-core-migrations
```

(optional) You can publish seeders and factories via:
``` bash
php artisan vendor:publish --tag=laravel-cms-core-database
```

(optional) You can publish routes via:
``` bash
php artisan vendor:publish --tag=laravel-cms-core-routes
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you've found a bug regarding security please mail [contact@leobsst.fr](mailto:contact@leobsst.fr) instead of using the issue tracker.

## Credits

- [LEOBSST](https://github.com/LEOBSST)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
