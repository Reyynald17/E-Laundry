<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::apiResource('order', OrderController::class);
Route::patch('order/{id}/status', [OrderController::class, 'updateStatus']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');