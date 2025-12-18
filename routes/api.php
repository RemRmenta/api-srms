<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceRequestController;

Route::get('/requests', [ServiceRequestController::class, 'index']);
Route::post('/requests', [ServiceRequestController::class, 'store']);
Route::get('/requests/{id}', [ServiceRequestController::class, 'show']);
Route::put('/requests/{id}', [ServiceRequestController::class, 'update']);
Route::delete('/requests/{id}', [ServiceRequestController::class, 'destroy']);