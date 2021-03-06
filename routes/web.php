<?php

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

Route::get('/',[\App\Http\Controllers\IndexController::class,'index']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('web/orders/{id}',[\App\Http\Controllers\WebOrderDetailController::class,'show'])->name('orders.web');
