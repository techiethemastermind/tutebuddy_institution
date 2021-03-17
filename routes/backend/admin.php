<?php

/*
 * All route names are prefixed with 'admin.'.
 */

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

//===== Institution Management Routes =====//
Route::resource('institutions', 'InstitutionController');

//===== User Management Routes =====//
Route::resource('users','UserController');
Route::get('admins', 'UserController@admins')->name('users.admins');
Route::get('teachers', 'UserController@teachers')->name('users.teachers');
Route::get('students', 'UserController@students')->name('users.students');
Route::get('ajax/admins','UserController@getAdminsByAjax')->name('users.adminsByAjax');
Route::get('ajax/teachers','UserController@getTeachersByAjax')->name('users.teachersByAjax');
Route::get('ajax/students','UserController@getStudentsByAjax')->name('users.studentsByAjax');
Route::get('account', 'UserController@myAccount')->name('myaccount');
Route::post('account/{id}/update', 'UserController@updateAccount')->name('myaccount.update');
Route::get('my/instructors', 'UserController@studentInstructors')->name('student.instructors');
Route::get('ajax/my-instructors', 'UserController@getStudentInstructorsByAjax')->name('student.getStudentInstructorsByAjax');
Route::post('users/csv/{type}', 'UserController@importCSV')->name('users.import.csv');

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
Route::post('ajax/create-class', 'ClassController@createClass')->name('ajax.createClass');
Route::get('classes-generate', 'ClassController@generate')->name('classes.generate');

//===== Courses Routes =====//
Route::resource('courses', 'CourseController');
Route::get('courses/restore/{id}', 'CourseController@restore')->name('courses.restore');
Route::get('courses/get/favorites', 'CourseController@favorites')->name('courses.favorites');
Route::get('ajax/courses/list/{type}', 'CourseController@getList')->name('getCoursesByAjax');
Route::get('ajax/courses/publish/{id}', 'CourseController@publish')->name('courses.publish');
Route::get('ajax/courses/delete/forever/{id}', 'CourseController@foreverDelete')->name('courses.foreverDelete');
Route::get('ajax/course/add-favorite/{course_id}', 'CourseController@addFavorite')->name('course.addFavorite');
Route::get('ajax/course/remove-favorite/{course_id}', 'CourseController@removeFavorite')->name('course.removeFavorite');
Route::get('my/courses', 'CourseController@studentCourses')->name('student.courses');
Route::get('ajax/my-courses', 'CourseController@getStudentCoursesByAjax')->name('student.getMyCoursesByAjax');
Route::Post('courses/complete', 'CourseController@complete')->name('courses.complete');

//===== Lessons Routes =====//
Route::resource('lessons', 'LessonController');
Route::get('lessons/delete/{id}', 'LessonController@deleteLesson')->name('lessons.delete');
Route::get('lessons/lesson/{id}', 'LessonController@getLesson')->name('lesson.getById');
Route::get('steps/delete/{id}', 'LessonController@deleteStep')->name('steps.delete');
Route::get('ajax/lessons-by-course', 'LessonController@getLessons')->name('lessons.getLessonsByCourse');
Route::get('my/live-sessions', 'LessonController@studentLiveSessions')->name('student.liveSessions');
Route::get('ajax/live-sessions/{type}', 'LessonController@getStudentLiveSessionsByAjax')->name('student.getLiveSessionsByAjax');

//===== Schedule Routes ====//
Route::get('schedule', 'ScheduleController@index')->name('schedule');
Route::get('schedule/source', 'ScheduleController@getScheduleData')->name('getScheduleData');
Route::post('schedule/new', 'ScheduleController@storeSchedule')->name('storeSchedule');
Route::post('schedule/lesson/add', 'ScheduleController@addLesson')->name('addLesson');
Route::post('schedule/update', 'ScheduleController@updateSchedule')->name('updateSchedule');
Route::get('schedule/delete', 'ScheduleController@deleteSchedule')->name('removeSchedule');

Route::get('get/course/lessons', 'ScheduleController@getLessons')->name('getLessonsByCourse');

Route::get('messages/get/enroll-thread', 'MessagesController@getEnrollThread')->name('messages.getEnrollThread');
Route::post('messages/enroll-send', 'MessagesController@sendEnrollChat')->name('messages.sendEnrollChat');

// Discussion
Route::resource('discussions', 'DiscussionController');
Route::get('topics', 'DiscussionController@getTopics')->name('discussions.topics');
Route::get('ajax/discussions', 'DiscussionController@getTopicsByAjax')->name('table.getTopicsByAjax');
Route::post('ajax/comment', 'DiscussionController@postComment')->name('ajax.postComment');
Route::get('ajax/similar', 'DiscussionController@getSimilar')->name('ajax.getSimilar');

//===== Assignment Routes =====//
Route::resource('assignments', 'AssignmentsController');
Route::get('assignments/restore/{id}', 'AssignmentsController@restore')->name('assignment.restore');
Route::get('ajax/assignments/list/{type}', 'AssignmentsController@getList')->name('getAssignmentsByAjax');
Route::get('ajax/assignments/publish/{id}', 'AssignmentsController@publish')->name('assignment.publish');
Route::get('ajax/assignments/delete/forever/{id}', 'AssignmentsController@foreverDelete')->name('assignment.foreverDelete');
Route::get('ajax/assignments/lessons', 'AssignmentsController@getLessons')->name('assignment.getLessonsByCourse');
Route::get('submited-assignments', 'AssignmentsController@submitedAssignments')->name('instructor.submitedAssignments');
Route::get('ajax/submited-assignments/{type}', 'AssignmentsController@getSubmitedAssignmentsByAjax')->name('instructor.getSubmitedAssignmentsByAjax');
Route::get('submited-assignments/result/{id}', 'AssignmentsController@show_result')->name('assignments.show_result');
Route::post('assignment-result/answer', 'AssignmentsController@result_answer')->name('assignments.result_answer');
Route::get('my/assignments', 'AssignmentsController@studentAssignments')->name('student.assignments');
Route::get('ajax/my-assignments/{type}', 'AssignmentsController@getStudentAssignmentsByAjax')->name('student.getMyAssignmentsByAjax');

//===== Test Routes =====//
Route::resource('tests', 'TestController');
Route::get('tests/restore/{id}', 'TestController@restore')->name('test.restore');
Route::get('ajax/tests/list/{type}', 'TestController@getList')->name('getTestsByAjax');
Route::get('ajax/test/publish/{id}', 'TestController@publish')->name('test.publish');
Route::get('ajax/test/delete/forever/{id}', 'TestController@foreverDelete')->name('test.foreverDelete');
Route::get('ajax/test/lessons', 'TestController@getLessons')->name('test.getLessonsByCourse');
Route::get('my/tests', 'TestController@studentTests')->name('student.tests');
Route::get('ajax/my-tests/{type}', 'TestController@getStudentTestsByAjax')->name('student.getMyTestsByAjax');
Route::get('submited-tests', 'TestController@submitedTests')->name('instructor.submitedTests');
Route::get('ajax/submited-tests/{type}', 'TestController@getSubmitedTestsByAjax')->name('instructor.getSubmitedTestsByAjax');
Route::get('submited-tests/result/{id}', 'TestController@show_result')->name('tests.show_result');
Route::post('test-result/answer', 'TestController@result_answer')->name('tests.result_answer');

//===== Questions Routes =====//
Route::resource('questions', 'QuestionController');
Route::get('ajax/questions/list/{course_id}/{test_id}', 'QuestionController@getList')->name('getQuestionsByAjax');
Route::get('ajax/question/{question_id}', 'QuestionController@getQuestion')->name('getQuestionByAjax');
Route::get('questions/delete/{id}', 'QuestionController@delete')->name('questions.delete');
Route::get('questions/restore/{id}', 'QuestionController@restore')->name('questions.restore');
Route::post('questions/add-section', 'QuestionController@addSection')->name('questions.addsection');

//===== Questions Options Routes =====//
Route::resource('questions_options', 'QuestionOptionsController');
Route::get('questions_options/delete/{id}', 'QuestionOptionsController@delete')->name('questions_options.delete');

//===== Quiz Routes =====//
Route::resource('quizs', 'QuizController');
Route::get('ajax/quizs/restore/{id}', 'QuizController@restore')->name('quizs.restore');
Route::get('ajax/quizs/publish/{id}', 'QuizController@publish')->name('quizs.publish');
Route::get('ajax/quizs/list/{type}', 'QuizController@getList')->name('getquizzesByAjax');
Route::get('ajax/quizs/delete/forever/{id}', 'QuizController@foreverDelete')->name('quizs.foreverDelete');
Route::get('my/quizs', 'QuizController@studentQuizs')->name('student.quizs');
Route::get('ajax/my-quizs/{type}', 'QuizController@getStudentQuizsByAjax')->name('student.getMyQuizzesByAjax');

//===== Timetable Routes =====//
Route::get('timetables/class', 'TimetableController@getClassTimeTable')->name('timetables.class');
Route::get('timetables/class/{id}/show', 'TimetableController@showClassTimeTable')->name('timetables.class.show');
Route::get('timetables/class/{id}/edit', 'TimetableController@editClassTimeTable')->name('timetables.class.edit');
Route::delete('timetables/class/{id}/delete', 'TimetableController@deleteClassTimeTable')->name('timetables.class.delete');
Route::post('timetables/class/{id}/update', 'TimetableController@updateClassTimeTable')->name('timetables.class.update');
Route::get('ajax/classTimeTables', 'TimetableController@getClassTimetableByAjax')->name('ajax.getClassTimetableByAjax');

Route::get('timetables/exam', 'TimetableController@getExamTimeTable')->name('timetables.exam');
Route::get('timetables/exam/{id}/show', 'TimetableController@showExamTimeTable')->name('timetables.exam.show');
Route::get('timetables/exam/{id}/edit', 'TimetableController@editExamTimeTable')->name('timetables.exam.edit');
Route::post('timetables/exam/{id}/update', 'TimetableController@updateExamTimeTable')->name('timetables.exam.update');
Route::post('timetables/exam/create', 'TimetableController@storeTimeTable')->name('timetables.store');
Route::post('ajax/examTimeTables/order', 'TimetableController@orderChange')->name('ajax.timetables.order');

Route::get('my/timetables', 'TimetableController@studentTimetables')->name('student.timetables');

//=== Result Sheet ===//
Route::get('results', 'ResultsController@student')->name('results.student');
Route::get('ajax/results', 'ResultsController@getStudentTableData')->name('results.getTableDataByAjax');
Route::get('results/detail/{id}', 'ResultsController@getResultDetail')->name('results.detail');

//=== My Badges ===//
Route::get('badges', 'ResultsController@badges')->name('results.student.badges');

// Certificate
Route::get('certificates', 'CertificateController@index')->name('certificates.index');
Route::get('certificate/{id}/show', 'CertificateController@show')->name('certificates.show');
Route::get('ajax/certificates', 'CertificateController@getCertificates')->name('table.getCertsByAjax');
Route::post('certificates/generate', 'CertificateController@generateCertificate')->name('certificates.generate');
Route::get('certificates/download', ['uses' => 'CertificateController@download', 'as' => 'certificates.download']);

// Workspace for Teachers
Route::get('live-sessions/all', 'LessonController@instructorLiveSessions')->name('instructor.liveSessions');

// Messages Routes
Route::get('messages', 'MessagesController@index')->name('messages.index');
Route::get('messages/users/{key}', 'MessagesController@getUsers')->name('messages.users');
Route::get('messages/get', 'MessagesController@getMessages')->name('messages.get');
Route::get('messages/last', 'MessagesController@lastMessages')->name('messages.last');

Route::post('messages/reply', 'MessagesController@reply')->name('messages.reply');
Route::post('messages/unread', 'MessagesController@getUnreadMessages')->name('messages.unread');

Route::get('messages/get/enroll-thread', 'MessagesController@getEnrollThread')->name('messages.getEnrollThread');
Route::post('messages/enroll-send', 'MessagesController@sendEnrollChat')->name('messages.sendEnrollChat');