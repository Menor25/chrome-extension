<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileProcessController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('upload')->namespace('App\Http\Controllers\FileProcessController')->group(function () {
    Route::get('video', [FileProcessController::class, 'index'])->name('index');
    Route::get('video/{id}', [FileProcessController::class, 'show'])->name('show');
    Route::post('video', [FileProcessController::class, 'store'])->name('store');
    Route::patch('video/{id}', [FileProcessController::class, 'update'])->name('update');
    Route::delete('video/{id}', [FileProcessController::class, 'destroy'])->name('destroy');
});
