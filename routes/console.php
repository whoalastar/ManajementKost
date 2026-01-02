<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily invoice status update
use Illuminate\Support\Facades\Schedule;

Schedule::command('invoices:update-status')->dailyAt('00:01');

