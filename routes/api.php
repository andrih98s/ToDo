<?php

use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/users',[UserController::class,'registration']);
Route::post('/users/login',[UserController::class,'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/users/logout/{id}', [UserController::class, 'logout']);});
Route::get('/users/all',[UserController::class,'index']);
