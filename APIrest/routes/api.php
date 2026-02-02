<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ActivityController;

Route::prefix('v1')->group(function() {

    Route::post('/login', LoginController::class);

    Route::middleware('auth:api')->group(function() {
        Route::get('/me', function() {
            return response()->json([
                'ok' => true,
            ]);
        });

    Route::get('/dashboard', function () {
            return response()->json([]);
    });

    Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

    Route::get('/activities', [ActivityController::class, 'index']);
    Route::post('/activities', [ActivityController::class, 'store']);
    Route::get('/activities/{activity}', [ActivityController::class, 'show']);
    Route::patch('/activities/{activity}', [ActivityController::class, 'update']);
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);

    });
});

?>