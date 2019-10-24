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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user', 'UserController@createUserLocation');
Route::get('/create-user', 'UserController@store');

Route::get('/products', 'ProductController@index');
Route::get('/products/store', 'ProductController@store');
Route::get('/products/edit/{id}', 'ProductController@update');
Route::get('/products/{id}', 'ProductController@show');
Route::get('/products/item/{id}', 'ProductController@item');
Route::get('/order/create', 'OrderController@create');
Route::get('/order/{id}', 'OrderController@index');
Route::get('/order/{id}/{item}', 'OrderController@deletarItem');
Route::get('/order/edit/{id}/{item}', 'OrderController@update');


