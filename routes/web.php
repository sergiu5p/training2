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

Route::get('/index', [
    'uses' => 'ProductController@index',
    'as' => 'product.index'
]);

Route::get('/index/{id}', [
    'uses' => 'ProductController@store',
    'as' =>'product.addToCart'
]);
