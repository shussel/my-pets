<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('pets', PetController::class)->middleware(['auth', 'verified']);

Route::get('/pets/{pet}/settings', [PetController::class, 'settings'])->middleware([
    'auth', 'verified'
])->name('pets.settings');

Route::patch('/pets/{pet}/settings', [PetController::class, 'saveSettings'])->middleware([
    'auth', 'verified'
])->name('pets.saveSettings');

Route::get('/pets/{pet}/schedule', [PetController::class, 'schedule'])->middleware([
    'auth', 'verified'
])->name('pets.schedule');

Route::patch('/pets/{pet}/schedule', [ScheduleController::class, 'update'])->middleware([
    'auth', 'verified'
])->name('schedule.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/phpinfo', function () {
    return phpinfo();
});
