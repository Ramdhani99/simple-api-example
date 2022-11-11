<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);

    Route::get('user', function () {
        // dd(request()->bearerToken());
        return response()->json(
            array_merge(
                (array) json_decode(json_encode(request()->user()->load(['role']))),
                ['abilites' => request()->user()->currentAccessToken()->abilities],
            ),
            200
        );
        // return request()->user()->load(['role']);
    });

    Route::resource('/products', ProductController::class)->except(['create', 'edit'])->middleware('abilities:admin');
    Route::post('/products/bulk-delete', [ProductController::class, 'bulk_delete'])->middleware('abilities:admin');
    Route::post('/products/bulk-show', [ProductController::class, 'bulk_show'])->middleware('abilities:admin');
    // Route::get('/test-admin', function(){
    //     return 'admin';
    // })->middleware('abilities:admin');
    // Route::get('/test-user', function(){
    //     return 'user';
    // })->middleware('abilities:member');
});

Route::post('/login', [AuthenticationController::class, 'login']);
