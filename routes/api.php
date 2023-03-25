<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(MovieController::class)->group(function () {
    Route::get('movies', 'index');
    Route::get('movies/{id}', 'show');
    Route::post('movies', 'store');
    Route::put('movies/{id}', 'update');
    Route::delete('movies/{id}', 'destroy');
    Route::get('search-movies', 'search');
});

Route::get('/', function () {
    return "Moviestore API";
});