<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {

    return $request->user();
})->middleware('auth:sanctum');


Route::get('/users', [AuthController::class, 'index']);
Route::post('/register', [AuthController::class, 'Register']);
Route::get('/login',[AuthController::class,'login']);
