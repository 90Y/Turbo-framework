<?php

Route::get('/', 'Site');
Route::get('/shop/add', 'Site/add', 'shop');//uri   controller   group
Route::post('/shop/add', 'Site/add', 'shop');
Route::get('/inner', function ($app) {$res = req('shop', '/shop/add?age=16', 'post', ['age'=>'18']); return $res;}); // PHP 5.3.0之后版本

//Route::get('/user', function ($app) {return 'shop-user_callback';}); // PHP 5.3.0之后版本
//Route::get('/', function ($app) {return 'shop-/';}); // PHP 5.3.0之后版本
//Route::get('/a', function ($app) {return 'shop-a';}); // PHP 5.3.0之后版本
//DB::post('/', 22);  // PHP 5.3.0之后版本
//Route::post('/admin', function () {return 'dddddd';}); // PHP 5.3.0之后版本
