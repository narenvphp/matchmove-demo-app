<?php

use Illuminate\Support\Facades\Route;

// login api
Route::post('/login', 'AdminAuthController@login');
// token apis
Route::middleware('auth:sanctum')->prefix('tokens')->group(function () {
    Route::get('/overview', 'TokenController@overview');
    Route::post('/generate', 'TokenController@generate');
    Route::put('/recall/{hash}', 'TokenController@recall');
});
// token verification api
Route::middleware('throttle:client')->group(function () {
    Route::get('/validate-token/{hash}', 'TokenController@validateToken');
});
