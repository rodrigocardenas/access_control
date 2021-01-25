<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::apiResource('/building', 'BuildingController');
// Route::apiResource('/user', 'UserController');
Route::apiResource('building', BuildingController::class)->middleware('auth:api');
Route::apiResource('user', UserController::class)->middleware('auth:api');

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/user/{user}/storeAccess', 'UserController@storeAccess');
