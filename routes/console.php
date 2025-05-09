<?php

use App\Models\Contest;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose(description: 'Display an inspiring quote');

Schedule::call(function () {
    Contest::where('ended_at', '<=', now())
        ->where('is_ended', 0)
        ->update(['is_ended' => 1]);
})->everyMinute();
