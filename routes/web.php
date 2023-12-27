<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canResetPassword' => Route::has('password.request'),
    ]);
});*/

//Route::get('/pets', function () {
//    return Inertia::render('Pets');
//})->middleware(['auth', 'verified'])->name('pets');
//
//Route::get('/pets/add', function () {
//    return Inertia::render('Pets');
//})->middleware(['auth', 'verified'])->name('pets.add');

Route::resource('pets', PetController::class)->middleware(['auth', 'verified']);

//Route::get('pets/{pet}', [PetController::class,'show'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/phpinfo', function () {
    return phpinfo();
});
