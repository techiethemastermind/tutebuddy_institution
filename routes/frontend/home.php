<?php

//===== Frontend Course Routes =====//
Route::get('course/{slug}', 'CoursesController@show')->name('courses.show');
Route::get('course/{course_slug}/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
Route::get('lesson/live/{lesson_slug}/{lesson_id}', 'LessonsController@liveSession')->name('lessons.live');

Route::post('courses/{id}/review', 'CoursesController@addReview')->name('courses.review');
Route::get('courses/review/{id}/edit', 'CoursesController@editReview')->name('courses.review.edit');
Route::post('courses/review/{id}/edit', 'CoursesController@updateReview')->name('courses.review.update');
Route::get('courses/review/{id}/delete', 'CoursesController@deleteReview')->name('courses.review.delete');

// === Profile route === //
Route::get('profile/{uuid}', 'UserController@getTeacherProfile')->name('profile.show');

// ==== Search Result ====//
Route::get('search/courses', 'SearchController@courses')->name('courses.search');
Route::get('search/instructors', 'SearchController@teachers')->name('teachers.search');

// Assignment
Route::get('assignment/{lesson_slug}/{id}', 'StudentController@startAssignment')->name('student.assignment.show');
Route::post('assignment/save', 'StudentController@saveAssignment')->name('student.assignment.save');
Route::get('assignment-result/{lesson_slug}/{test_id}', 'StudentController@assignmentResult')->name('student.assignment.result');

// Take Quiz for Student
Route::get('quiz/{lesson_slug}/{quiz_id}', 'StudentController@startQuiz')->name('student.quiz.show');
Route::post('quiz/save', 'StudentController@saveQuiz')->name('student.quiz.save');
Route::get('quiz-result/{lesson_slug}/{quiz_id}', 'StudentController@quizResult')->name('student.quiz.result');

// Take test for Student
Route::get('test/{lesson_slug}/{test_id}', 'StudentController@startTest')->name('student.test.show');
Route::post('test/save', 'StudentController@saveTest')->name('student.test.save');
Route::get('test-result/{lesson_slug}/{test_id}', 'StudentController@TestResult')->name('student.test.result');

Route::get('lesson/{id}/complete', 'LessonsController@completeLesson')->name('lesson.complete');
Route::get('ajax/step/{id}/complete/{type}', 'LessonsController@completeStep')->name('ajax.step.complete');