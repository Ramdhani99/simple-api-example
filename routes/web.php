<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'is_token_exist'], function () {
    Route::get('/', [AuthenticationController::class, 'index']);
    Route::post('/', [AuthenticationController::class, 'login'])->name('login');
});

Route::group(['middleware' => 'is_login'], function () {
    Route::resource('home', HomeController::class)->except(['show', 'create', 'edit']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
});

