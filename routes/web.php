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

// {tag} is indetify for Institution
Route::get('{tag}', 'FrontController@index')->name('frontpage');

Auth::routes();

// Admin
Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'namespace' => 'Backend', 'middleware' => ['auth']], function () {
    include_route_files(__DIR__ . '/backend/');
});

Route::group(['namespace' => 'Frontend'], function () {
    include_route_files(__DIR__ . '/frontend/');
});