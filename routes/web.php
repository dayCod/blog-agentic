<?php

use App\Http\Controllers\BlogController;
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
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::post('/', [BlogController::class, 'store'])->name('store');
    });
});

require __DIR__.'/settings.php';
