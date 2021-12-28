<?php

use App\Services\Route;

Route::get('/books', 'BookController@getAll');
Route::get('/book/:sku', 'BookController@get');
Route::get('/book/take/:sku', 'BookController@take');
Route::get('/book/return/:sku', 'BookController@return');
Route::post('/login', 'UserController@login');
Route::get('/my-books', 'UserController@getBooks');
Route::get('/user/books/:id', 'UserController@getUserBooks');