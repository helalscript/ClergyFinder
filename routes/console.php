<?php

use App\Console\Commands\LoanRepaymentStatusUpdate;
use App\Services\LiveSessionQueueService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');
// Schedule::call( function () {
//     logger()->info('test it');
// })->everySecond();

Schedule::call(function () {
    app(LiveSessionQueueService::class)->purgeStaleQueueEntries();
})->everyMinute();
