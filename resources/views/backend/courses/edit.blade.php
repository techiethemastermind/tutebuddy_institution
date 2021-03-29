@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<style>
.modal .modal-body {
    max-height: 80vh;
    overflow: auto;
}

.select2-container {
    display: block;
}

.accordion .btn-actions {
    margin: 0 10px;
}
[dir=ltr] .step-menu {
    box-shadow: 0 0 2px 0px black;
}
[dir=ltr] .step-menu:before, [dir=ltr] .step-menu:after {
    opacity: 0 !important;
}
</style>

@endpush
<?php $live_available = false; ?>
<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Course</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">Courses</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.courses.index') }}"
                        class="btn btn-outline-secondary">Back</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.schedule') }}" class="btn btn-outline-secondary">Set Schedule</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.courses.update', $course->id], 'files' => true, 'id'
            => 'frm_course']) !!}
            <div class="row">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">Edit Course</div>
                    </div>

                    <label class="form-label">Course Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg" placeholder="Course Title" value="{{ $course->title }}" tute-no-empty>
                    </div>

                    <label class="form-label">Course Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description">{{ $course->short_description }}</textarea>
                        <small class="form-text text-muted">Shortly describe this course.</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">About Course</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0">{!! $course->description !!}</div>
                        <small class="form-text text-muted">describe about this course.</small>
                    </div>

                    <!-- Lessons -->

                    <div class="page-separator">
                        <div class="page-separator__text">Lessons</div>
                    </div>

                    <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent">

                        <!-- Lesson Items -->
                        @foreach($course->lessons as $lesson)
                        <div class="accordion__item" lesson-id="{{ $lesson->id }}">
                            <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                data-target="#lesson-toc-{{ $lesson->id }}" data-parent="#parent">
                                <span class="flex">{{ $lesson->position }}. {{ $lesson->title }}</span>
                                <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                            </a>
                            <div class="accordion__menu collapse" id="lesson-toc-{{ $lesson->id }}">
                                <div class="accordion__menu-link">
                                    <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                    <span class="flex">
                                        @php
                                        if (strlen($lesson->short_text) > 60)
                                        $description = substr($lesson->short_text, 0, 60) . '...';
                                        else
                                        $description = $lesson->short_text;
                                        @endphp

                                        {{ $description }}
                                    </span>
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($lesson->updated_at))->diffForHumans() }}
                                    </span>
                                    <span class="btn-actions">
                                        <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, 1]) }}"
                                            class="btn btn-outline-secondary btn-sm btn-preview" target="_blank">
                                            <i class="material-icons">remove_red_eye</i>
                                        </a>
                                        <a href="{{ route('admin.lesson.getById', $lesson->id) }}"
                                            class="btn btn-outline-secondary btn-sm btn-edit">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="{{ route('admin.lessons.delete', $lesson->id) }}"
                                            class="btn btn-outline-secondary btn-sm btn-delete">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" id="btn_add_lesson"
                        class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ Add Lesson</button>
                </div>

                <!-- Side bar for information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <button type="button" id="btn_save_course" class="btn btn-accent">Save Draft</button>
                            <button type="button" id="btn_publish_course" class="btn btn-primary">Publish</button>
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-info">Preview</a>
                        </div>

                        <div class="list-group list-group-flush">
                            @if($course->published == 0)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            @endif
                            @if($course->published == 1)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Published</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            @endif
                        </div>

                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Options</div>
                    </div>

                    <div class="card">
                        <div class="card-body options">
                            <!-- Select Teacher -->
                            <div class="form-group">
                                <label class="form-label">Teacher</label>
                                <select name="teacher" class="form-control">
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->fullName() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Class -->
                            <div class="form-group">
                                <label class="form-label">Class</label>
                                <select name="class" class="form-control">
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Time Setting</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Date -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Start Date:</label>
                                            <input name="start_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="{{ $course->start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">End Date:</label>
                                            <input name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="{{ $course->end_date }}">
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Course will start and end date</small>
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">Your Timezone</label>
                                <select name="timezone" class="form-control"></select>
                                <small class="form-text text-muted">Select timezone</small>
                            </div>

                            <!-- Repeat -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_repeat" type="checkbox" class="custom-control-input"
                                        @if($course->repeat) checked="true" @endif>
                                    <label for="chk_repeat" class="custom-control-label form-label">Repeat</label>
                                    <input type="hidden" name="repeat" value="{{ $course->repeat }}">
                                </div>
                            </div>

                            <div class="form-group" for="chk_repeat">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <input type="number" name="repeat_value" value="{{ $course->repeat_value }}"
                                            class="form-control" min="1">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <select id="custom-select" name="repeat_type"
                                            class="form-control custom-select">
                                            <option value="week" @if($course->repeat_type == 'week') selected
                                                @endif>Weeks</option>
                                            <option value="month" @if($course->repeat_type == 'month') selected
                                                @endif>Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Thumbnail</div>
                    </div>

                    <div class="card">
                        <img id="display_course_image" src="@if(!empty($course->course_image)) 
                                    {{asset('/storage/uploads/' . $course->course_image ) }}
                                 @else 
                                    {{asset('/assets/img/no-image.jpg')}}
                                 @endif" id="img_course_image" width="100%" alt="">
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="course_image" id="course_file_image" class="custom-file-input"
                                    data-preview="#display_course_image">
                                <label for="course_file_image" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Video</div>
                    </div>

                    <div class="card">
                        @if(!empty($course->mediaVideo))
                        <div class="embed-responsive embed-responsive-16by9">
                            <?php
                                $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                $embed->setAttribute([
                                    'id'=>'iframe_course_video',
                                    'class'=>'embed-responsive-item',
                                    'allowfullscreen' => ''
                                ]);
                            ?>
                            {!! $embed->getHtml() !!}
                        </div>
                        <div class="card-body">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url"
                                data-video-preview="#iframe_course_video"
                                value="{{ $course->mediaVideo->url }}" placeholder="Enter Video URL">
                            <small class="form-text text-muted">Enter a valid video URL.</small>
                        </div>
                        @else
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_course_video" src=""
                                allowfullscreen="">
                            </iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url" value=""
                                data-video-preview="#iframe_course_video"
                                placeholder="Enter Video URL">
                            <small class="form-text text-muted">Enter a valid video URL.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<!-- Add Lesson Modal -->
<div class="modal fade" id="modal_lesson" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true, 'id'
                =>'frm_lesson']) !!}

                <div class="row">
                    <div class="col-12 col-md-8 mb-3">
                        <div class="form-group">
                            <label class="form-label">Title:</label>
                            <input type="text" name="lesson_title"
                                class="form-control form-control-lg @error('lesson_title') is-invalid @enderror"
                                placeholder="@lang('labels.backend.courses.fields.title')" value="">
                            @error('lesson_title')
                            <div class="invalid-feedback">Title is required field.</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Short Description:</label>
                            <textarea class="form-control" name="lesson_short_description" rows="3"></textarea>
                        </div>

                        <div class="form-group" id="lesson_contents"></div>

                        <div class="form-group">
                            <div class="flex" style="max-width: 100%">
                                <div class="btn-group" id="lesson_add_step" style="width: 100%;">
                                    <button type="button" class="btn btn-block btn-outline-secondary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+ Add Step
                                    </button>
                                    <div class="dropdown-menu step-menu" style="width: 100%;">
                                        <a class="dropdown-item" href="javascript:void(0)" section-type="video">Video
                                            Section</a>
                                        <a class="dropdown-item" href="javascript:void(0)" section-type="text">Full Text
                                            Section</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">

                        <div class="form-group">
                            <label class="form-label">Options</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Appointment</label>
                                        <select name="lesson_schedule" class="form-control">
                                            <option value="">Select available time</option>
                                            @foreach($schedules as $schedule)
                                            <?php if($schedule['available'] == true) {
                                                $live_available = true;
                                            } ?>
                                            <option value="{{ $schedule['id'] }}" @if($schedule['available'] !='true' )
                                                disabled="disabled" @endif>{{ $schedule['content'] }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">you can select available time slots</small>
                                    </div>
                                    @if($live_available)
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input id="chk_liveLesson" type="checkbox" name="chk_live_lesson" value="0"
                                                class="custom-control-input">
                                            <label for="chk_liveLesson" class="custom-control-label">Check this for Live
                                                Session</label>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group" for="dv_liveLesson" style="display: none;">
                                        <span class="text-muted"></span>
                                        <input type="hidden" class="form-control" name="live_lesson" value="0">
                                        <p class="mt-2">
                                            <a href="" target="_blank" class="btn btn-primary btn-md">Create Room</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Thumbnail</label>
                            <div class="card">
                                <img src="{{asset('/assets/img/no-image.jpg')}}" width="100%"
                                    id="display_lesson_image" alt="">
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" id="lesson_file_image" name="lesson_file_image"
                                            class="custom-file-input" data-preview="#display_lesson_image">
                                        <label for="file" class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Introduce Video</label>
                            <div class="card">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item no-video lesson-video"
                                        id="iframe_lesson_intro_video" src="" allowfullscreen=""></iframe>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">URL</label>
                                    <input type="text" class="form-control" name="lesson_intro_video"
                                        data-video-preview="#iframe_lesson_intro_video"
                                        value="" placeholder="Enter Video URL">
                                    <small class="form-text text-muted">Enter a valid video URL.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Download File</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" id="lesson_file_download" name="lesson_file_download"
                                            class="custom-file-input">
                                        <label for="file" class="custom-file-label">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Max file size is 5MB.</small>
                                </div>
                            </div>
                        </div>

                        <!-- hidden Informations for Lesson -->
                        <input type="hidden" name="lesson_full_description">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_lesson">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- jQuery Mask Plugin -->
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

// Init Elements
$(function() {

    // Global Variable for this page
    var course_quill;
    var status = {
        lesson_id: '',
        lesson_modal: 'new',
        lesson_step: 0,
        lesson_current: ''
    };

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    // Init Quill Editor for Course description
    course_quill = new Quill('#course_editor', {
        theme: 'snow',
        placeholder: 'Course description',
        modules: {
            toolbar: toolbarOptions
        }
    });

    $('select[name="teacher"]').select2();
    $('select[name="class"]').select2();

    // Timezone
    $('select[name="timezone"]').timezones();
    if('{{ auth()->user()->timezone }}' != '') {
        $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();
    }

    // Schedule Select on Lesson Modal
    $('select[name="lesson_schedule"]').select2();

    // Repeat course
    $('#chk_repeat').on('change', function(e) {
        var repeat_val = $(this).prop('checked') ? '1' : '0';
        $('input[name="repeat"]').val(repeat_val);
        var style = $(this).prop('checked') ? 'block' : 'none';
        $('div[for="chk_repeat"]').css('display', style);
    });

    $('#btn_save_course').on('click', function(e) {
        e.preventDefault();
        save_course('draft');
    });

    $('#btn_publish_course').on('click', function(e) {
        e.preventDefault();
        save_course('pending');
    });

    // Event when click save course button id="btn_save_course"
    function save_course(action) {
        $('#frm_course').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = course_quill.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'course_description',
                    type: 'text',
                    value: content
                });

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });
            },
            success: function(res) {

                if (res.success) {
                    swal("Success!", "Successfully Updated!", "success");
                } else {
                    swal("Error!", res.message, "error");
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                swal("Error!", errMsg, "error");
            }
        });
    }

    // Add New lesson
    $('#btn_add_lesson').on('click', function(e) {
        if(status.lesson_current != 'new') {
            init_lesson_modal();
        }
        status.lesson_modal = 'new';
        $('#modal_lesson').modal('toggle');
    });

    // Lesson Edit
    $('#parent').on('click', 'a.btn-edit', function(e){

        e.preventDefault();
        var url = $(this).attr('href');
        status.lesson_modal = 'edit';
        status.lesson_id = $(this).closest('.accordion__item').attr('lesson-id');

        // Current Lesson
        if(status.lesson_current == status.lesson_id) {
            $('#modal_lesson').modal('toggle');
            return false;
        }

        init_lesson_modal();

        // Get new lesson information
        $.ajax({
            method: 'GET',
            url: url,
            success: function(res) {

                if (res.success) {

                    // Set Lesson Modal Contents
                    $('#frm_lesson').find('input[name="lesson_title"]').val(res.lesson.title);
                    $('#frm_lesson').find('textarea[name="lesson_short_description"]').val(res.lesson.short_text);

                    if (res.schedule) {
                        $('#frm_lesson').find('select[name="lesson_schedule"]').val(res.schedule.id).change();
                    }
                    
                    if (res.lesson.image != undefined && res.lesson.image != '') {
                        $('#display_lesson_image').attr('src', '{{ config("app.url") }}' + 'storage/uploads/' + res.lesson.image);
                    } else {
                        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
                    }

                    if (res.lesson.video != null) {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val(res.lesson.video).change();
                    } else {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').addClass('no-video');
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val('');
                        $('#frm_lesson').find('iframe.lesson-video').attr('src', '');
                    }

                    if(res.lesson.lesson_type == 1) {
                        $('#chk_liveLesson').prop('checked', true);
                        var live_url = '{{ config("app.url") }}' + 'lesson/live/' + res.lesson.slug + '/' + res.lesson.id;
                        $('div[for="dv_liveLesson').find('.text-muted').text(live_url);
                        $('div[for="dv_liveLesson').find('a').attr('href', live_url);
                        $('div[for="dv_liveLesson').css('display', 'block');
                        $('input[name="live_lesson"]').val('1');
                    } else {
                        $('#chk_liveLesson').prop('checked', false);
                        $('div[for="dv_liveLesson').find('.text-muted').text('');
                        $('div[for="dv_liveLesson').find('a').attr('href', '#');
                        $('div[for="dv_liveLesson').css('display', 'none');
                        $('input[name="live_lesson"]').val('0');
                    }

                    // add Steps
                    var lesson_step = 0;
                    if(res.steps.length > 0) {

                        var lesson_contents = $('#lesson_contents');

                        $.each(res.steps, function(idx, item) {

                            lesson_step = idx + 1;
                            var ele_sep = `<div class="page-separator">
                                                <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                                            </div>`;

                            if(item.type == 'text') {

                                var ele = `<div class="form-group step" section-type="text" data-step-id="`+ item.id +`">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Text:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Content:</label>
                                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0">`+ item.text +`</div>
                                                        <input type="hidden" name="lesson_description_id__` + lesson_step + `" value="`+ item.id +`">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'video') {

                                var ifrm_video = '<iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>';

                                if(item.video != '') {
                                    
                                    ifrm_video = `<iframe class="embed-responsive-item lesson-video" src=""
                                         allowfullscreen="" id="step_video_`+ lesson_step +`"></iframe>`;
                                }

                                var ele = `<div class="form-group step" section-type="video" data-step-id="`+ item.id +`">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Video:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for video step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Video:</label>
                                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                                            ` + ifrm_video + `
                                                        </div>
                                                        <label class="form-label">URL</label>
                                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" 
                                                        value="` + item.video + `" placeholder="Enter Video URL"
                                                        data-video-preview="#step_video_`+ lesson_step +`">
                                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                                        <input type="hidden" name="lesson_video_id__` + lesson_step + `" value="`+ item.id +`">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            lesson_contents.append($(ele));
                        });

                        var editors = lesson_contents.find('div[id*="lesson_editor__"]');
                        $.each(editors, function(idx, item) {
                            var id = $(item).attr('id');
                            var step = id.slice(id.indexOf('__'));
                            var quill_editor = new Quill('#' + id, {
                                theme: 'snow',
                                placeholder: 'Lesson description',
                                modules: {
                                    toolbar: toolbarOptions
                                }
                            });
                        });

                        var selects = lesson_contents.find('select[name*="test__"]');
                        $.each(selects, function(idx, item) {
                            var val = $(item).attr('data-selected');
                            $(this).val(val).change();
                        });

                        var step_videos = lesson_contents.find('input.step-video');
                        $.each(step_videos, function(idx, item) {
                            $(this).change();
                        });
                    }

                    status.lesson_step = lesson_step;
                    status.lesson_current = res.lesson.id;
                    status.lesson_slug = res.lesson.slug;
                    $('#modal_lesson').modal('toggle');
                }
            }
        });
    });

    // Add steps
    $('#lesson_add_step').on('click', 'a.dropdown-item', function(e) {

        status.lesson_step++;

        var ele_sep = `<div class="page-separator">
                            <div class="page-separator__text"> Step: ` + status.lesson_step + `</div>
                        </div>`;

        var ele_text = `<div class="form-group step" section-type="text">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Text:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_description_title__` + status.lesson_step + `" 
                                            value="" placeholder="title for step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Content:</label>
                                        <div style="min-height: 200px;" id="lesson_editor__` + status.lesson_step + `" class="mb-0"></div>
                                        <textarea name="lesson_description__` + status.lesson_step + `" style="display: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_description_duration__` + status.lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_video = `<div class="form-group step" section-type="video">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Video:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_video_title__` + status.lesson_step + `" 
                                            value="" placeholder="title for video step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Video:</label>
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen="" 
                                            id="iframe_`+ status.lesson_step +`"></iframe>
                                        </div>
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control step-video" name="lesson_video__`+ status.lesson_step +`" 
                                        value="" placeholder="Enter Video URL" data-video-preview="#iframe_`+ status.lesson_step +`">
                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_video_duration__` + status.lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var type = $(this).attr('section-type');

        switch(type) {
            case 'text':
                $('#lesson_contents').append($(ele_text));

                // Init Quill Editor for Lesson Full description
                var lesson_quill = new Quill('#lesson_editor__' + status.lesson_step, {
                    theme: 'snow',
                    placeholder: 'Lesson description',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
                break;
            case 'video':
                $('#lesson_contents').append($(ele_video));
                break;
            case 'test':
                $('#lesson_contents').append($(ele_test));
                break;
        }
    });

    // Delete Step
    $('#lesson_contents').on('click', 'button.close', function(e) {

        var step_ele = $(this).closest('.form-group');
        var step_id = step_ele.attr('data-step-id');

        $.ajax({
            method: 'get',
            url: '/admin/steps/delete/' + step_id,
            success: function(res) {

                step_ele.toggle( function() { 
                    
                    $(this).remove();

                    // Adjust Steps:
                    var steps = $('#lesson_contents').find('div.step');
                    $.each(steps, function(idx, item) {
                        idx++;
                        $(item).find('.page-separator__text').text('Step: ' + idx);
                        status.lesson_step = idx;
                    });
                });
                console.log(res);
            }
        });
    });

    // Delete a Lesson
    $('.accordion').on('click', '.accordion__item a.btn-delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var accordion_item = $(this).closest('div.accordion__item');

        swal({
            title: "Are you sure?",
            text: "This lesson will removed from this course",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            dangerMode: false,
        }, function(val) {
            if (val) {
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function(res) {
                        if (res.success) {
                            accordion_item.remove();
                        }
                    }
                });
            }
        });
    });

    // Click save in modal
    $('#btn_save_lesson').on('click', function(e) {

        e.preventDefault();

        $('#frm_lesson').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var editors = formObject.find('div[id*="lesson_editor__"]');
                $.each(editors, function(idx, item) {
                    var id = $(item).attr('id');
                    var step = id.slice(id.indexOf('__'));

                    var lesson_editor = new Quill('#' + id);

                    formData.push({
                        name: 'lesson_description' + step,
                        type: 'text',
                        value: lesson_editor.root.innerHTML
                    });
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: status.lesson_modal
                });

                if(status.lesson_modal == 'edit') {
                    formData.push({
                        name: 'lesson_id',
                        type: 'int',
                        value: status.lesson_id
                    });
                }
            },
            beforeSend: function() {
                // console.log('Before Send');
            },
            uploadProgress: function(event, position, total, percentComplete) {
                // console.log(percentComplete);
            },
            success: function(res) {

                if (res.success) {
                    if(res.action == 'new') {
                        var lesson_html = `
                            <div class="accordion__item" lesson-id="`+ res.lesson.id +`">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#lesson-toc-` + res.lesson.id + `" data-parent="#parent">
                                    <span class="flex">` + res.lesson.position + `. ` + res.lesson.title + `</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="lesson-toc-` + res.lesson.id + `">
                                    <div class="accordion__menu-link">
                                        <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                        <a class="flex" href="#">` + res.lesson.short_text.slice(0, 60) + `</a>
                                        <span class="text-muted">Just Now</span>
                                        <span class="btn-actions">
                                            <a href="/dashboard/lessons/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-preview">
                                                <i class="material-icons">remove_red_eye</i>
                                            </a>
                                            <a href="/dashboard/lessons/lesson/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="/dashboard/lessons/delete/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-delete">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#parent').append($(lesson_html));

                        status.lesson_current = res.lesson.id;
                        localStorage.setItem('steps__' + res.lesson_id, status.lesson_step);
                    }
                    
                    $('#modal_lesson').modal('toggle');

                } else {
                    swal('Warning!', res.message, 'warning');
                }
            },
            error: function(err) {
                swal("Error!", err, "error");
            }
        });
    });

    // Lesson Modal Title Validation
    $('#frm_lesson').on('keyup', 'input[name="lesson_title"]', function() {
        $(this).removeClass('is-invalid');
        $('#frm_lesson').find('div.invalid-feedback').remove();
    });

    $('#chk_liveLesson').on('change', function(e) {

        if(status.lesson_modal == 'edit') {
            if($(this).prop('checked')) {
                var live_url = '{{ config("app.url") }}' + 'lesson/live/' + status.lesson_slug + '/' + status.lesson_id;
                $('div[for="dv_liveLesson').find('.text-muted').text(live_url);
                $('div[for="dv_liveLesson').find('a').attr('href', live_url);
                $('div[for="dv_liveLesson').css('display', 'block');
                $('input[name="live_lesson"]').val('1');
            } else {
                $('div[for="dv_liveLesson').css('display', 'none');
                $('input[name="live_lesson"]').val('0');
            }
        } else {
            if($(this).prop('checked')) {
                $('input[name="live_lesson"]').val('1');
            } else {
                $('input[name="live_lesson"]').val('0');
            }
        }
    });

    function init_lesson_modal() {
        status.lesson_step = 0;
        status.lesson_current = 'new';
        $('#frm_lesson').find('input[name="lesson_title"]').val('');
        $('#frm_lesson').find('textarea').val('');
        $('#frm_lesson').find('select').val('').change();
        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
        $('#lesson_contents').html('');

        $('#chk_liveLesson').prop('checked', false);
        $('div[for="dv_liveLesson').find('.text-muted').text('');
        $('div[for="dv_liveLesson').find('a').attr('href', '#');
        $('div[for="dv_liveLesson').css('display', 'none');
        $('input[name="live_lesson"]').val('0');
    }
});

</script>

@endpush