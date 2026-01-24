<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;

Route::prefix('v1')->group(function() {

    Route::post('/login', LoginController::class);

    Route::middleware('auth')->group(function() {
        Route::get('/me', function() {
            return response()->json([
                'ok' => true,
            ]);
        });
    });
});

?>