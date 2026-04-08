<?php

use App\Ai\Agents\FinanceJournalist;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('journalist', function () {

    $response = FinanceJournalist::make()
        ->prompt(
            prompt: 'Please fetch the content from the following url: https://finance.yahoo.com/news/oil-prices-crater-after-trump-announces-two-week-ceasefire-in-us-iran-war-230021802.html',
            timeout: 120,
        );
    $this->info($response->text);
});
