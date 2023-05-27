<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/users', [UserController::class, 'index']);
Route::delete('/users/{id}/delete', [UserController::class, 'delete']);
Route::get('/transactions', [TransactionController::class, 'index']);
