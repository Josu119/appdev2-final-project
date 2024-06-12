<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->group(function () {

    // Route::get('/todos', [TodoController::class, 'index']);

    Route::post('/todos', [TodoController::class, 'store']);

    Route::get('/todos/{todo}', [TodoController::class, 'show']);

    Route::put('/todos/{todo}', [TodoController::class, 'update']);

    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
});


Route::post('/register', [RegisterController::class, 'register']);

Route::get('/users', [UserController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



// testing
// Get all of the to-do list:
Route::get('/todosnatos', [TodoController::class, 'index']);

// updating some
// Route::put('/todos/{todo}', [TodoController::class, 'update']);



