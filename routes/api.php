<?php

use App\Http\Controllers\Api\ZohoController;
use Illuminate\Support\Facades\Route;

Route::post('/zoho/create', [ZohoController::class, 'createDealAndAccount']);
