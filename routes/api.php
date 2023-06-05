<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [PassportAuthController::class, 'register']);

Route::post('login', [PassportAuthController::class, 'login']);

Route::post('createDetail', [DetailController::class, 'createDetail']);

// Route::middleware('auth:api')->group(function () {
//     Route::resource('users', UserController::class);
// });



Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class);
});
Route::group(['middleware' => ['auth:api']], function () {
    // Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    // Route::resource('products', ProductController::class);
});
