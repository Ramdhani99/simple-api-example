<?php

use App\Http\Livewire\Main\Login;
use App\Http\Livewire\Main\Products;
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
    Route::get('/', Login::class)->name('login');
});

Route::group(['middleware' => 'is_login'], function () {
    // Route::resource('home', HomeController::class)->except(['show', 'create', 'edit']);
    Route::get('home', Products::class);
});
