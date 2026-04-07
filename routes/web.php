<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::group([
        'prefix' => 'blog',
        'as' => 'blog.',
    ], function () {
        Route::inertia('/', 'Blog/Index')->name('index');
    });
});

require __DIR__.'/settings.php';
