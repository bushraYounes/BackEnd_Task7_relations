<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
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


Route::apiResource('users',UserController::class);

// Route::get('projects',[ProjectController::class, 'index']);
// Route::post('projects',[ProjectController::class, 'store']);
// Route::put('projects',[ProjectController::class, 'update']);
// Route::delete('projects',[ProjectController::class, 'delete']);

Route::apiResource('projects', ProjectController::class);
Route::apiResource('categories', CategoryController::class);