<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('ads:recalculate-scores')
    ->everyFiveMinutes()
    ->withoutOverlapping()   // verhindert Parallelläufe falls einer mal länger braucht
    ->runInBackground();     // blockiert den Scheduler-Tick nicht
