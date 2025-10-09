<?php

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

// Speech submission (no CSRF; use for cURL or JS fetch)
use App\Http\Controllers\ExerciseController;
Route::post('/exercises/submit', [ExerciseController::class, 'submit'])->name('exercises.submit');

Route::post('/exercises/submit', [\App\Http\Controllers\ExerciseController::class, 'submit']);
