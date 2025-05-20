<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoAuthController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/oauth2/callback', [ZohoAuthController::class, 'handleCallback']);
Route::get('/zoho/refresh-token', [ZohoAuthController::class, 'refreshAccessToken']);
