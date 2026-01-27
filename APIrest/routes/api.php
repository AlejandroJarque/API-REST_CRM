<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\V1\ActivityController;

Route::prefix('v1')->group(function() {

    Route::post('/login', LoginController::class);

    Route::middleware('auth:api')->group(function() {
        Route::get('/me', function() {
            return response()->json([
                'ok' => true,
            ]);
        });

        Route::get('/activities', [ActivityController::class, 'index']);
        Route::post('/activities', [ActivityController::class, 'store']);
        Route::get('/activities/{activity}', [ActivityController::class, 'show']);
        Route::patch('/activities/{activity}', [ActivityController::class, 'update']);
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);
    });
});

?>