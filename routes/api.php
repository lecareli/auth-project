<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

Route::get('/user', [AuthController::class, 'me'])->name('me')->middleware('auth:sanctum');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::post('/register', [AuthController::class, 'register'])->name('register');
