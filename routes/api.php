<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleriesController;
use App\Http\Controllers\CommentsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(
    function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/refresh', 'refresh');
        Route::get('/me', 'getActiveUser');
        Route::post('/logout', 'logout');
    }
);

Route::controller(GalleriesController::class)->group(
    function () {
        Route::get('/galleries', 'index');
        Route::get('/galleries/{id}', 'show');
        Route::post('/galleries', 'store');
        Route::put('/galleries/{id}', 'update');
        Route::delete('/galleries/{id}', 'destroy');
    }
);

Route::controller(CommentsController::class)->group(
    function () {
        Route::post('galleries/{id}/comments', 'store');
        Route::delete('comments/{id}', 'destroy');
    }
);