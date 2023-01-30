<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// PROJECT
Route::prefix('project')->group(function () {
    Route::post('/add-project', [ProjectController::class, 'addProject']);
    Route::post('/pay-project', [ProjectController::class, 'payProject']);
});


