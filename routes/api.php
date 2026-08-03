<?php

use App\Http\Controllers\Api\ApiServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/services', [ApiServiceController::class, 'index']);
Route::get('/services/{service}', [ApiServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/services', [ApiServiceController::class, 'store']);
    Route::put('/services/{service}', [ApiServiceController::class, 'update']);
    Route::delete('/services/{service}', [ApiServiceController::class, 'destroy']);
});
