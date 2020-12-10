<?php

//===== Frontend Course Routes =====//
Route::get('course/{slug}', 'CoursesController@show')->name('courses.show');
Route::get('course/{course_slug}/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
Route::get('lesson/live/{lesson_slug}/{lesson_id}', 'LessonsController@liveSession')->name('lessons.live');

// === Profile route === //
Route::get('profile/{uuid}', 'UserController@getTeacherProfile')->name('profile.show');