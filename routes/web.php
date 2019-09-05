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

Route::get('/spa', function () {
   return view('index');
});

Route::get('/', function () {
   return redirect()->route('product.show');
});

Route::get('/index', [
    'uses' => 'ProductController@show',
    'as' => 'product.show'
]);

Route::resource('cart', 'CartController')->only(['index', 'store', 'destroy']);

Route::get('/login', [
   'uses' => 'LoginController@login',
    'as' => 'login'
]);

Route::post('/login', [
   'uses' => 'LoginController@checkLogin',
   'as' => 'checkLogin'
]);

Route::get('/logout', [
    'uses' => 'LoginController@logout',
    'as' => 'logout'
]);

Route::get('/products', [
    'uses' => 'ProductController@products',
    'as' => 'product.products'
]);

// delete
Route::get('/delete/{id}', [
   'uses' => 'ProductController@destroy',
   'as' => 'product.destroy'
]);

Route::get('/edit/{id?}', [
    'uses' => 'ProductController@edit',
    'as' => 'product.edit'
]);

// Fix that
Route::post('/update/{id?}', [
    'uses' => 'ProductController@update',
    'as' => 'update'
]);

Route::resource('orders', 'OrderController')->only(['index', 'show', 'store']);
