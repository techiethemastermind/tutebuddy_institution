<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Assignment;
use App\Models\AssignmentResult;
use App\Models\TestResultAnswers;
use App\Models\Quiz;
use App\Models\QuizResults;
use App\Models\Grade;

class DashboardController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Dashboard
    public function index()
    {
        if(auth()->user()->hasRole('Institution Admin')) {
            $courses = Course::all();
            $course_ids = Course::pluck('id');
            $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
            $schedules = Schedule::whereIn('lesson_id', $lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

            $course_students = DB::table('course_student')->whereIn('course_id', $course_ids)->get();
            $students = collect();
            foreach($course_students as $item) {
                $c_item = Course::find($item->course_id);
                $u_item = User::find($item->user_id);
                $data = [
                    'course' => $c_item,
                    'user' => $u_item
                ];
                $students->push($data);
            }

            $assignments = Assignment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit(5)->get();
            $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');
            $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->limit(5)->get();
            $test_ids = Test::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $testResults = TestResult::whereIn('test_id', $test_ids)->limit(5)->get();
            $quiz_ids = Quiz::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $quizResults = QuizResults::whereIn('quiz_id', $quiz_ids)->limit(5)->get();

            $teachers_count = User::role('Teacher')->where('institution_id', auth()->user()->institution_id)->count();
            $students_count = User::role('Student')->where('institution_id', auth()->user()->institution_id)->count();
            $courses_count = Course::where('published', 1)->count();
            $classes_count  = Grade::count();

            return view('backend.dashboard.institution', 
                compact(
                    'teachers_count',
                    'students_count',
                    'courses_count',
                    'classes_count',
                    'courses',
                    'schedules',
                    'students',
                    'assignments', 
                    'assignment_results',
                    'testResults',
                    'quizResults'
                )
            );
        }

        if(auth()->user()->hasRole('Teacher')) {
            $courses = Course::all();
            $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $course_ids = Course::whereIn('id', $course_ids)->pluck('id');
            $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
            $schedules = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

            $students = collect();
            foreach($courses as $course) {
                $course_grade = $course->grade;
                $course_grade_students = $course_grade->students;

                foreach($course_grade_students as $item) {
                    $data = [
                        'class' => $course_grade,
                        'user' => $item
                    ];

                    $students->push($data);
                }
            }

            $assignments = Assignment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit(5)->get();
            $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');
            $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->limit(5)->get();
            $test_ids = Test::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $testResults = TestResult::whereIn('test_id', $test_ids)->limit(5)->get();
            $quiz_ids = Quiz::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $quizResults = QuizResults::whereIn('quiz_id', $quiz_ids)->limit(5)->get();

            return view('backend.dashboard.teacher', 
                compact(
                    'courses',
                    'schedules',
                    'students',
                    'assignments', 
                    'assignment_results',
                    'testResults',
                    'quizResults'
                )
            );
        }

        if(auth()->user()->hasRole('Student')) {

            // Get purchased Course IDs
            $course_ids = Course::all();
            $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
            $teachers_id = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');

            $purchased_courses = Course::whereIn('id', $course_ids)->get();
            $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
            $schedules = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

            $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->limit(5)->get();
            $teachers = User::whereIn('id', $teachers_id)->limit(5)->get();
            $testResults = TestResult::where('user_id', auth()->user()->id)->limit(4)->get();

            return view('backend.dashboard.student',
                compact(
                    'purchased_courses',
                    'schedules',
                    'assignments',
                    'teachers',
                    'testResults'
                )
            );
        }

        return view('backend.dashboard.admin');
    }
}
