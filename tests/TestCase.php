<?php

namespace Leobsst\LaravelCmsCore\Tests;

use Leobsst\LaravelCmsCore\Providers\LaravelCmsCoreServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Configure error handling properly for tests
        if (! defined('PHPUNIT_COMPOSER_INSTALL')) {
            ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
            ini_set('display_errors', '1');
        }

        // Ensure clean state for each test
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    protected function tearDown(): void
    {
        // Clean up any global state
        if (class_exists(\Mockery::class)) {
            \Mockery::close();
        }

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCmsCoreServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('app.debug', true);
        config()->set('app.env', 'testing');
        config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        // Set default log configuration
        config()->set('logging.default', 'testing');
        config()->set('logging.channels.testing', [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ]);

        // Configure laravel-cms-core defaults for testing
        config()->set('laravel-cms-core.log_dirs', [storage_path('logs')]);
        config()->set('laravel-cms-core.max_file_size', 1024 * 1024); // 1MB
        config()->set('laravel-cms-core.limit', 1000);
    }
}
