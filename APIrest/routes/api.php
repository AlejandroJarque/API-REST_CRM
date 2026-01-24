<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\V1\ClientController;

Route::prefix('v1')->group(function() {

    Route::post('/login', LoginController::class);

    Route::middleware('auth:api')->group(function() {
        Route::get('/me', function() {
            return response()->json([
                'ok' => true,
            ]);
        });
        Route::get('/clients', [ClientController::class, 'index']);
    });
});

?>