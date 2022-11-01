<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        return request()->user();
    });

    Route::resource('/products', ProductController::class)->except(['create', 'edit'])->middleware('abilities:admin');
    // Route::get('/test-admin', function(){
    //     return 'admin';
    // })->middleware('abilities:admin');
    // Route::get('/test-user', function(){
    //     return 'user';
    // })->middleware('abilities:member');
});

Route::post('/login', [AuthenticationController::class, 'login']);