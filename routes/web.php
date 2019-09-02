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
    'uses' => 'ProductController@show',
    'as' => 'product.show'
]);

Route::post('/index', [
    'uses' => 'OrderController@sendMail',
    'as' => 'sendMail'
]);

Route::get('/add/{id}', [
    'uses' => 'ProductController@store',
    'as' => 'product.addToCart'
]);

Route::get('/cart', [
    'uses' => 'ProductController@showCart',
    'as' => 'product.showCart'
]);

Route::get('/remove/{id}', [
    'uses' => 'ProductController@removeFromCart',
    'as' => 'product.remove'
]);

Route::get('/login', [
   'uses' => 'LoginController@login',
    'as' => 'login'
]);

Route::post('/login', [
   'uses' => 'LoginController@checkLogin',
   'as' => 'checkLogin'
]);

Route::match(array('GET', 'POST'), '/products', [
    'uses' => 'ProductController@products',
    'as' => 'product.products'
]);

Route::get('/delete/{id}', [
   'uses' => 'ProductController@destroy',
   'as' => 'product.destroy'
]);

Route::get('/edit/{id}', [
    'uses' => 'ProductController@edit',
    'as' => 'product.edit'
]);
