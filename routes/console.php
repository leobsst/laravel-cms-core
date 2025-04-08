<?php

use Illuminate\Support\Facades\Schedule;

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