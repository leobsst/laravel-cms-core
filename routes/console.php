<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Run Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here is where queued tasks are defined for your application.
|
*/
Schedule::command('queue:work --queue=emails --stop-when-empty --tries=3 --backoff=5')
    ->everySecond()
    ->withoutOverlapping();

Schedule::command('queue:work --queue=high --stop-when-empty --tries=3 --backoff=5')
    ->everySecond()
    ->withoutOverlapping();

Schedule::command('queue:work --queue=default --stop-when-empty --tries=3 --backoff=5')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('queue:flush --hours=48')
    ->daily()
    ->withoutOverlapping();

// Converts all images to webp format from /public/assets/img folder
Schedule::command('images:convert-to-webp')
    ->dailyAt(time: '03:00')
    ->withoutOverlapping()
    ->onOneServer();

// Terminates all logs older than 24 hours
Schedule::command('logs:terminate')
    ->dailyAt(time: '06:00')
    ->withoutOverlapping()
    ->onOneServer();
