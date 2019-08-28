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

Route::get('/', [
    'uses' => 'ProductController@show',
    'as' => 'product.show'
]);

Route::get('/add/{id}', [
    'uses' => 'ProductController@store',
    'as' =>'product.addToCart'
]);

Route::get('/cart', [
    'uses' => 'ProductController@showCart',
    'as' => 'product.showCart'
]);

Route::get('/remove/{id}', [
    'uses' => 'ProductController@destroy',
    'as' => 'product.remove'
]);
