<?php

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('films', \App\Http\Controllers\FilmsController::class);
    Route::get('/films/{id}/cu',[\App\Http\Controllers\FilmsController::class, 'createAndUpdate'])->middleware(
        'can:' . \App\Models\Permissions::PERMISSION_ADD_FILMS . ',App\Models\Films'
    );
    Route::post('/films/update', [\App\Http\Controllers\FilmsController::class, 'update'])->name('films.update');
    Route::get('rating', [\App\Http\Controllers\RatingsController::class, 'index'])->name('rating.index');
    // Route::get('/rating/', \App\Http\Controllers\RatingsController::class, 'index');
    Route::get('/rating/{filmIdentifier}/cu', [\App\Http\Controllers\RatingsController::class, 'rate']);
    Route::post('/rating/update', [\App\Http\Controllers\RatingsController::class, 'update']);
});
