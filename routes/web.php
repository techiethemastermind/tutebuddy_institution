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

// Admin is prefix for Institution
Route::get('/', 'FrontController@index')->name('homepage');

// {prefix} is prefix for Institution
Route::get('{prefix}', 'FrontController@login')->name('loginPage');

Auth::routes();

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Backend', 'middleware' => ['auth']], function () {
	    include_route_files(__DIR__ . '/backend/');
	}
);

Route::group(['namespace' => 'Frontend'], function () {
    include_route_files(__DIR__ . '/frontend/');
});