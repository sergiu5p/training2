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

Route::group(['middleware' => 'login'], function () {

    Route::resource('orders', 'OrderController')->only(['index', 'show']);

    Route::post('/logout', [
        'uses' => 'LoginController@logout',
        'as' => 'logout'
    ]);

    Route::get('/products', [
        'uses' => 'ProductController@products',
        'as' => 'product.products'
    ]);

    Route::delete('/delete/{id}', [
        'uses' => 'ProductController@destroy',
        'as' => 'product.destroy'
    ]);

    Route::get('/edit/{id}', [
        'uses' => 'ProductController@edit',
        'as' => 'product.edit'
    ]);

    Route::get('/products/create', [
        'uses' => 'ProductController@create',
        'as' => 'product.create'
    ]);

    Route::post('/products', [
        'uses' => 'ProductController@store',
        'as' => 'product.store'
    ]);

    Route::post('/update/{id}', [
        'uses' => 'ProductController@update',
        'as' => 'update'
    ]);
});

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

Route::post('/orders', [
    'uses' => 'OrderController@store',
    'as' => 'orders.store'
]);
