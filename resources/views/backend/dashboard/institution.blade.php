@extends('layouts.app')

@section('content')

@push('after-styles')
<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.dashboard.title')</h2>

                    <ol class="breadcrumb p-0 m-0">

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.dashboard.title')
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">
            <div class="row row mb-32pt">
                <div class="col-lg-3">
                    <a href="{{ route('admin.users.teachers') }}">
                        <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $teachers_count }}</h4>
                                <div><label class="form-label">Teachers</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.users.students') }}">
                        <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $students_count }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.students')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.courses.index') }}">
                        <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $courses_count }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.active_courses')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.classes.index') }}">
                        <div class="card border-1 border-left-3 border-left-dark text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $classes_count }}</h4>
                                <div><label class="form-label">Grades</label></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">

        @if(count($courses) > 0)
        <div class="page-section">

            <!-- Live Lessons -->
            @if(count($schedules) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.upcomming_lessons')</strong></p>
                    <a href="{{ route('admin.instructor.liveSessions') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time"
                    data-lists-sort-desc="true">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>
                                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-time">
                                        @lang('labels.backend.dashboard.table.date')
                                    </a>
                                </th>
                                <th>@lang('labels.backend.dashboard.table.start_time')</th>
                                <th>@lang('labels.backend.dashboard.table.end_time')</th>
                                <th>@lang('labels.backend.dashboard.table.course_title')</th>
                                <th>@lang('labels.backend.dashboard.table.lesson_title')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="schedule_list">
                            @foreach($schedules as $schedule)
                            <tr>
                                <td></td>
                                <td>
                                    <?php
                                        $new_date = new DateTime;
                                        $dayofweek = strtolower(App\Models\Schedule::WEEK_DAYS[\Carbon\Carbon::parse($schedule->date)->dayOfWeek]);
                                        $new_date->modify($dayofweek . ' this week');
                                    ?>
                                    <strong>
                                        {{ App\Models\Schedule::WEEK_DAYS[\Carbon\Carbon::parse($schedule->date)->dayOfWeek] }}, 
                                        {{ \Carbon\Carbon::parse($new_date)->toFormattedDateString() }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ timezone()->convertFromTimezone($schedule->start_time, $schedule->timezone, 'H:i:s') }}</strong>
                                </td>
                                <td>
                                    <strong>{{ timezone()->convertFromTimezone($schedule->end_time, $schedule->timezone, 'H:i:s') }}</strong>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($schedule->course->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $schedule->course->title }}</strong></small>
                                                <small
                                                    class="js-lists-values-location text-50">{{ $schedule->course->teachers[0]->name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-accent text-white">
                                                {{ substr($schedule->lesson->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $schedule->lesson->title }}</strong></small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    if($schedule->lesson->lesson_type == 1) {
                                        $route = route('lessons.live', [$schedule->lesson->slug, $schedule->lesson->id]);
                                    } else {
                                        $route = route('lessons.show', [$schedule->course->slug, $schedule->lesson->slug, 1]);
                                    }
                                    
                                    $result = live_schedule($schedule->lesson);
                                    ?>
                                    @if($result['status'])
                                    <a href="{{ $route }}" target="_blank" class="btn btn-primary btn-sm">Join</a>
                                    @else
                                    <button type="button" class="btn btn-md btn-outline-primary" disabled>Scheduled</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Enrolled Students -->
            @if(count($students) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.students_roster')</strong></p>
                    <a href="{{ route('admin.instructor.students') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.table.name')</th>
                                <th>@lang('labels.backend.dashboard.table.course')</th>
                                <th>@lang('labels.backend.dashboard.table.start_date')</th>
                                <th>@lang('labels.backend.dashboard.table.end_date')</th>
                                <th>@lang('labels.backend.dashboard.table.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(!empty($student['user']->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $student['user']->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ substr($student['user']->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-name">{{ $student['user']->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $student['user']->email }}</small>
                                                </div>
                                                <div class="d-flex align-items-center ml-24pt">
                                                    <i class="material-icons text-20 icon-16pt">comment</i>
                                                    <small class="ml-4pt"><strong class="text-50">1</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong>{{ $student['course']->title }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $student['course']->start_date }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $student['course']->end_date }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">{{ $student['course']->progress() }}%</small>
                                        @if($student['course']->progress() > 99)
                                        <span class="indicator-line rounded bg-success"></span>
                                        @else
                                        <span class="indicator-line rounded bg-primary"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Assignments -->
            @if(count($assignments) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.assignments_for_students')</strong></p>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table id="tbl_assignment" class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.table.subject')</th>
                                <th>@lang('labels.backend.dashboard.table.due_date')</th>
                                <th>@lang('labels.backend.dashboard.table.total_marks')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="assignment">
                            @foreach($assignments as $assignment)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($assignment->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $assignment->title }}</strong></small>
                                                <small class="text-70">
                                                    Course: {{ $assignment->lesson->course->title }} |
                                                    Lesson: {{ $assignment->lesson->title }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>due date</td>
                                <td>{{ $assignment->total_mark }}</td>
                                <td>@include('layouts.buttons.show', ['show_route' => route('admin.assignments.show', $assignment->id)])</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Submitted Assignments -->
            @if(count($assignment_results) > 0)
            <div class="card">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.submitted_assignments')</strong></p>
                    <a href="{{ route('admin.instructor.submitedAssignments') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browse All</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table id="tbl_a_results" class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.subject')</th>
                                <th>@lang('labels.backend.dashboard.table.student')</th>
                                <th>@lang('labels.backend.dashboard.table.attachment')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($assignment_results as $result)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($result->assignment->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $result->assignment->title }}</strong></small>
                                                <small class="text-70">
                                                    Course: {{ $result->assignment->lesson->course->title }} |
                                                    Lesson: {{ $result->assignment->lesson->title }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(!empty($result->user->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $result->user->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ substr($result->user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-name">{{ $result->user->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $result->user->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $ext = pathinfo($result->attachment_url, PATHINFO_EXTENSION);
                                        if($ext == 'pdf') {
                                            $img = '<img class="rounded" src="'. asset('/images/pdf.png') .'" alt="image" style="width: 50px;">';
                                        } else {
                                            $img = '<img class="rounded" src="'. asset('/images/docx.png') .'" alt="image" style="width: 50px;">';
                                        }
                                    ?>
                                    @if(!empty($result->attachment_url))
                                    <a href="{{ asset('/storage/uploads/' . $result->attachment_url ) }}" target="_blank">{!! $img !!}</a>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td>@include('layouts.buttons.show', ['show_route' => route('admin.assignments.show', $result->assignment->id)])</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Test Result -->
            @if(count($testResults) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.submitted_tests')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.tests.index') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row">

                @foreach($testResults as $testResult)

                    <div class="card-group-row__col col-md-6">

                        <div class="card card-group-row__card card-sm">
                            <div class="card-body d-flex align-items-center">
                                <a href="{{ route('student.test.result', [ $testResult->test->lesson->slug, $testResult->test->id]) }}"
                                    class="avatar overlay overlay--primary avatar-4by3 mr-12pt">
                                    <img src="{{ asset('/storage/uploads/thumb/' . $testResult->test->course->course_image ) }}"
                                        alt="{{ $testResult->test->title }}" class="avatar-img rounded">
                                    <span class="overlay__content"></span>
                                </a>
                                <div class="flex mr-12pt">
                                    <a class="card-title" href="{{ route('student.test.result', [ $testResult->test->lesson->slug, $testResult->test->id]) }}">{{ $testResult->test->title }}</a>
                                    <div class="card-subtitle text-50">
                                        {{ Carbon\Carbon::parse($testResult->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="lead text-headings lh-1">By: {{ $testResult->user->name }}</span>
                                    <a class="card-title" href="{{ route('student.test.result', [ $testResult->test->lesson->slug, $testResult->test->id]) }}">
                                        <small class="text-50 text-uppercase text-headings">
                                            {{ $testResult->test->course->title }} | {{ $testResult->test->lesson->title }}
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
            @endif

            <!-- Quiz Result -->
            @if(count($quizResults) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.submitted_quizzes')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.quizs.index') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row">

                @foreach($quizResults as $quizResult)

                    <div class="card-group-row__col col-md-6">

                        <div class="card card-group-row__card card-sm">
                            <div class="card-body d-flex align-items-center">
                                <a href="{{ route('student.quiz.result', [ $quizResult->quiz->lesson->slug, $quizResult->quiz->id]) }}"
                                    class="avatar overlay overlay--primary avatar-4by3 mr-12pt">
                                    <img src="{{ asset('/storage/uploads/thumb/' . $quizResult->quiz->course->course_image ) }}"
                                        alt="{{ $quizResult->quiz->title }}" class="avatar-img rounded">
                                    <span class="overlay__content"></span>
                                </a>
                                <div class="flex mr-12pt">
                                    <a class="card-title" href="{{ route('student.quiz.result', [ $quizResult->quiz->lesson->slug, $quizResult->quiz->id]) }}">
                                        {{ $quizResult->quiz->title }}</a>
                                    <div class="card-subtitle text-50">
                                        {{ Carbon\Carbon::parse($quizResult->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="lead text-headings lh-1">By: {{ $quizResult->user->name }}</span>
                                    <a class="card-title" href="{{ route('student.quiz.result', [ $quizResult->quiz->lesson->slug, $quizResult->quiz->id]) }}">
                                        <small class="text-50 text-uppercase text-headings">
                                            {{ $quizResult->quiz->course->title }} | {{ $quizResult->quiz->lesson->title }}
                                        </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
            @endif

        </div>
        @endif

        @if(count($courses) < 1)
        <div class="page-section card card-body mt-64pt">
            <div class="row align-items-center">
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">1</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.create_course')</div>
                        <p class="card-subtitle text-black-70">Create your courses to teach</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">2</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.create_lesson')</div>
                        <p class="card-subtitle text-black-70">Create lessons under courses.</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">3</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.start_teaching')</div>
                        <p class="card-subtitle text-black-70">Create shedules to teach with live session.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-section">
            <div class="form-group text-center">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-block">Create your first course</a>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
$(function() {
    // 
});
</script>

@endpush

@endsection