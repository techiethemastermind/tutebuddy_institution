<?php

/*
 * All route names are prefixed with 'admin.'.
 */

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

//===== Institution Management Routes =====//
Route::resource('institutions', 'InstitutionController');

//===== User Management Routes =====//
Route::resource('users','UserController');
Route::get('teachers','UserController@getTeachers')->name('users.teachers');
Route::get('students','UserController@getStudents')->name('users.students');

//===== Roles Routes =====//
Route::resource('roles','RoleController');

//==== Settings Route ====//
Route::get('settings/general', 'ConfigController@getGeneralSettings')->name('settings.general');
Route::post('settings/general', 'ConfigController@saveGeneralSettings')->name('settings.general.save');
Route::get('settings/institution', 'ConfigController@getInstitutionSettings')->name('settings.institution');
Route::post('settings/institution', 'ConfigController@saveInstitutionSettings')->name('settings.institution.save');

//===== Class Routes =====//
Route::resource('classes','ClassController');
Route::get('ajax/get-classes', 'ClassController@getTableData')->name('ajax.getClassesTableData');