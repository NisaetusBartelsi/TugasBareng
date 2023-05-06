<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/admin', [AuthController::class, 'admin']);
Route::post('/regitrasi', [AuthController::class, 'registrasi']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/create', [BarangController::class, 'create'])->middleware('auth:sanctum');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::get('/read', [BarangController::class, 'read']);
Route::put('/update/{id}', [BarangController::class, 'update']);
Route::delete('/delete/{id}', [BarangController::class, 'delete']);
