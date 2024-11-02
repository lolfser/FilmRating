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

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('films', \App\Http\Controllers\FilmsController::class);
    Route::get('/films/{id}/cu',[\App\Http\Controllers\FilmsController::class, 'createAndUpdate'])->middleware(
        'can:' . \App\Models\Permissions::PERMISSION_ADD_FILMS . ',App\Models\Films'
    );
    Route::post('/films/update', [\App\Http\Controllers\FilmsController::class, 'update'])->name('films.update');

    Route::get('/rating/list/', [\App\Http\Controllers\RatingsController::class, 'index'])->name('rating.index');
    Route::get('/rating/{filmIdentifier}/cu', [\App\Http\Controllers\RatingsController::class, 'rate']);
    Route::post('/rating/update', [\App\Http\Controllers\RatingsController::class, 'update']);
    Route::post('/rating/load', [\App\Http\Controllers\RatingsController::class, 'load']);
    Route::get('/rating/export', [\App\Http\Controllers\ExportController::class, 'rating']);

    Route::get('/import/', [\App\Http\Controllers\ImportController::class, 'index']);
    Route::post('/import/', [\App\Http\Controllers\ImportController::class, 'import']);
    Route::get('/stats/', [\App\Http\Controllers\StatsController::class, 'index']);
    Route::get('/program/', [\App\Http\Controllers\ProgramblocksController::class, 'index']);
    Route::post('/program/save', [\App\Http\Controllers\ProgramblocksController::class, 'save']);
    Route::post('/program/load', [\App\Http\Controllers\ProgramblocksController::class, 'load']);
    Route::post('/program/filter', [\App\Http\Controllers\ProgramblocksController::class, 'filter']);
    Route::get('print/{metaId}', [\App\Http\Controllers\ExportController::class, 'print'])->name('export.print');
    Route::get('csv/{metaId}', [\App\Http\Controllers\ExportController::class, 'csv'])->name('export.csv');
});
