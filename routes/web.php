<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::get('/calendar/events', [CalendarController::class, 'events']);
    Route::resource('companies', CompanyController::class);
    Route::resource('events', EventController::class)->except(['index']);
});

require __DIR__.'/auth.php';
