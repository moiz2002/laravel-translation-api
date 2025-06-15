<?php

use App\Http\Controllers\API\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('translations')->group(function () {
    Route::get('/', [TranslationController::class, 'index']);          // Search translations
    Route::post('/', [TranslationController::class, 'store']);         // Create or update translation
    Route::get('/export', [TranslationController::class, 'exportJson']); // Export as JSON
});
