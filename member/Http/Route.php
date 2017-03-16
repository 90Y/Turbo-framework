<?php

Route::get('/user', function ($app) {return 'member-user_callback';}); // PHP 5.3.0之后版本
Route::get('/', function ($app) {$res = request('http://turbo-shop.com/shop/add?age=16',  ['age'=>'18'], 'POST'); return $res;}); // PHP 5.3.0之后版本
//Route::get('/', function ($app) {return 'member-a';}); // PHP 5.3.0之后版本
//DB::post('/', 22);  // PHP 5.3.0之后版本
Route::post('/admin', function () {return 'dddddd';}); // PHP 5.3.0之后版本
