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

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create Subject</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">Courses</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create
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
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true, 'id' => 'frm_course']) !!}
            <div class="row">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">Create Subject</div>
                    </div>

                    <label class="form-label">Subject Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg" placeholder="Course Title" value="" tute-no-empty>
                    </div>

                    <label class="form-label">Subject Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description"></textarea>
                        <small class="form-text text-muted">Shortly describe this subject. It will show under title</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">About Subject</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0"></div>
                        <small class="form-text text-muted">Describe about this subject. What you will teach?</small>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Lessons</div>
                    </div>

                    <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent"></div>
                    <button type="button" id="btn_add_lesson" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ Add Lesson</button>
                </div>

                <!-- Side bar for information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <button type="button" id="btn_save_course" class="btn btn-accent">Save Draft</button>
                            <button type="button" id="btn_publish_course" class="btn btn-primary">Publish Subject</button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted draft">clear</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Publish</strong></a>
                                <i class="material-icons text-muted publish">clear</i>
                            </div>
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
                        <div class="page-separator__text">Thumbnail</div>
                    </div>

                    <div class="card">
                        <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_course_image" alt="" width="100%">
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="course_image" id="course_image" class="custom-file-input" data-preview="#img_course_image">
                                <label for="course_image" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Introduction Video</div>
                    </div>

                    <div class="card">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_course_video" src="" allowfullscreen=""></iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url" value="" placeholder="Enter Video URL"
                                data-video-preview="#iframe_course_video">
                            <small class="form-text text-muted">Enter a valid video URL.</small>
                        </div>
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

                {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true, 'id' =>'frm_lesson']) !!}

                    <div class="row">
                        <div class="col-12 col-md-8 mb-3">
                            <div class="form-group">
                                <label class="form-label">Title:</label>
                                <input type="text" name="lesson_title" class="form-control form-control-lg" 
                                placeholder="Lesson Title" value="" tute-no-empty>
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
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">+ Add Step </button>
                                        <div class="dropdown-menu" style="width: 100%;">
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="video">Video Section</a>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="text">Full Text Section</a>
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
                                            <div class="custom-control custom-checkbox">
                                                <input id="chk_liveLesson" type="checkbox" name="chk_live_lesson" value="0" class="custom-control-input">
                                                <label for="chk_liveLesson" class="custom-control-label">Check this for Live
                                                    Session</label>
                                            </div>
                                        </div>
                                        <div class="form-group" for="dv_liveLesson" style="display: none;">
                                            <span class="text-muted"></span>
                                            <input type="hidden" class="form-control" name="live_lesson" value="0">
                                            <p class="mt-2">
                                                <a href="#" class="btn btn-primary btn-md">Go To Room</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Thumbnail</label>
                                <div class="card">
                                    <img src="{{asset('/assets/img/no-image.jpg')}}" id="display_lesson_image" width="100%" id="img_lesson_image" alt="">
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_image" name="lesson_file_image" class="custom-file-input"
                                                data-preview="#display_lesson_image">
                                            <label for="file" class="custom-file-label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Introduce Video</label>
                                <div class="card">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item no-video lesson-video" id="iframe_lesson_intro_video" src="" allowfullscreen=""></iframe>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control" id="lesson_intro_video" name="lesson_intro_video" value=""
                                            placeholder="Enter Video URL" data-video-preview="#iframe_lesson_intro_video">
                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Download File</label>
                                <div class="card">                                    
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_download" name="lesson_file_download" class="custom-file-input">
                                            <label for="file" class="custom-file-label">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Max file size is 5MB.</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_lesson" >Save</button>
            </div>
        </div>
    </div>
</div>

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

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
$(document).ready(function() {

    var course_id = '';
    var lesson_step = 1;
    var lesson_modal = 'new';
    var lesson_current = '';
    var $lesson_contents = $('#lesson_contents');
    var lesson_id = '';

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    // Init Quill Editor for Course description
    var course_quill = new Quill('#course_editor', {
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

    // Display Video for Lesson content
    $('#lesson_contents').on('change', 'input.step-video', function(e) {
        target = $(this).closest('.card-body').find('iframe.lesson-video');
        display_iframe($(this).val(), target);
    });

    // When click add Lesson button course should be saved draft first
    $('#btn_add_lesson').on('click', function(e) {

        e.preventDefault();

        if(course_id == '') {

            if(!isValidForm($('#frm_course'))) {
                return false;
            }

            // Save draft by ajax
            $('#frm_course').ajaxSubmit({
                beforeSerialize: function($form, options) {
                    // Before form Serialized
                },
                beforeSubmit: function(formData, formObject, formOptions) {

                    // Append quill data
                    formData.push({
                        name: 'course_description',
                        type: 'text',
                        value: course_quill.root.innerHTML
                    });
                },
                success: function(res) {
                    if(res.success) {
                        course_id = res.course_id;
                        $('#save_status .draft').text('check');
                        $('#modal_lesson').modal('toggle');
                    } else {
                        swal('Warning!', res.message, 'warning');
                    }
                }
            });
        } else {
            if(lesson_current != 'new') {
                init_lesson_modal();
            }
            lesson_modal = 'new';
            $('#modal_lesson').modal('toggle');
        }
    });

    // Lesson Edit
    $('#parent').on('click', 'a.btn-edit', function(e){

        e.preventDefault();
        var url = $(this).attr('href');
        lesson_modal = 'edit';
        lesson_id = $(this).closest('.accordion__item').attr('lesson-id');

        // Current Lesson
        if(lesson_current == lesson_id) {
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

                    if (res.lesson.image != '')
                        $('#display_lesson_image').attr('src',
                            'http://localhost:8000/storage/uploads/' + res.lesson.image);
                    else
                        $('#display_lesson_image').attr('src',
                            "{{asset('/assets/img/no-image.jpg')}}");

                            if (res.lesson.video != null) {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val(res.lesson.video).change();
                    } else {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').addClass('no-video');
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val('');
                        $('#frm_lesson').find('iframe.lesson-video').attr('src', '');
                    }

                    if(res.steps.length > 0) {

                        var lesson_contents = $('#lesson_contents');

                        $.each(res.steps, function(idx, item) {

                            var ele_sep = `<div class="page-separator">
                                                <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                                            </div>`;

                            if(item.type == 'text') {
                                var ele = `<div class="form-group step" section-type="text">
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

                                var ele = `<div class="form-group step" section-type="video">
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
                                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" value="" placeholder="Enter Video URL">
                                                        <small class="form-text text-muted">Enter a valid video URL.</small>
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

                            if(item.type == 'test') {
                                var ele = `<div class="form-group step" section-type="test">
                                        `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Test:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Title:</label>
                                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for test step">
                                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            lesson_contents.append($(ele));
                            lesson_step++;
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
                    }

                    lesson_step = lesson_step;
                    lesson_current = res.lesson.id;
                    $('#modal_lesson').modal('toggle');
                }
            }
        });
    });

    $('#btn_save_course').on('click', function(e) {
        e.preventDefault();
        store_course('draft');
    });

    $('#btn_publish_course').on('click', function(e) {
        e.preventDefault();
        store_course('publish');
    });

    // Add steps
    $('#lesson_add_step').on('click', 'a.dropdown-item', function(e) {

        var ele_sep = `<div class="page-separator">
                            <div class="page-separator__text"> Step: ` + lesson_step + `</div>
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
                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                            value="" placeholder="title for step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Content:</label>
                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0"></div>
                                        <textarea name="lesson_description__` + lesson_step + `" style="display: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
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
                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                            value="" placeholder="title for video step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Video:</label>
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>
                                        </div>
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" value="" placeholder="Enter Video URL">
                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_test = `<div class="form-group step" section-type="test">
                        `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Test:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Title:</label>
                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                            value="" placeholder="title for test step">
                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                </div>
                            </div>
                        </div>`;

        var type = $(this).attr('section-type');

        switch(type) {
            case 'text':
                $lesson_contents.append($(ele_text));
                var lesson_quill = new Quill('#lesson_editor__' + lesson_step, {
                    theme: 'snow',
                    placeholder: 'Lesson description',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
                break;
            case 'video':
                $lesson_contents.append($(ele_video));
                break;
            case 'quiz':
                $lesson_contents.append($(ele_quiz));
                break;
            case 'test':
                $lesson_contents.append($(ele_test));
                break;
        }

        lesson_step++;
    });

    $('#lesson_contents').on('click', 'button.close', function(e) {
        $(this).closest('.form-group').remove();

        // Adjust Steps:
        var steps = $('#lesson_contents').find('div.step');
        $.each(steps, function(idx, item) {
            idx++;
            $(item).find('.page-separator__text').text('Step: ' + idx);
            status.lesson_step = idx;
        });
    });

    // Adding New Lesson
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

                // Append Course ID
                formData.push({
                    name: 'course_id',
                    type: 'int',
                    value: course_id
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: lesson_modal
                });

                if(lesson_modal == 'edit') {
                    formData.push({
                        name: 'lesson_id',
                        type: 'int',
                        value: lesson_id
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

                if(res.success) {
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
                        lesson_current = res.lesson.id;
                        localStorage.setItem('steps__' + res.lesson_id, lesson_step);
                    }
                    
                    $('#modal_lesson').modal('toggle');

                } else {
                    swal('Warning!', res.message, 'warning');
                }
            }
        });
    });

    $('#chk_liveLesson').on('change', function(e) {
        if($(this).prop('checked')) {
            $('input[name="live_lesson"]').val('1');
        } else {
            $('input[name="live_lesson"]').val('0');
        }
    });

    function store_course(action) {

        if(!isValidForm($('#frm_course'))) {
            return false;
        }

        $('#frm_course').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var course_description = course_quill.root.innerHTML;

                // Append Quill Description
                formData.push({
                    name: 'course_description',
                    type: 'text',
                    value: course_description
                });

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });

                formData.push({
                    name: 'course_id',
                    type: 'string',
                    value: course_id
                });
            },
            success: function(res) {
                if(res.success) {
                    swal({
                        title: "Successfully Stored",
                        text: "It will redirected to Editor",
                        type: 'success',
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            var url = '/admin/courses/' + res.course_id + '/edit';
                            window.location.href = url;
                        }
                    });
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function init_lesson_modal() {
        lesson_step = 0;
        lesson_current = 'new';
        $('#frm_lesson').find('input[name="lesson_title"]').val('');
        $('#frm_lesson').find('textarea').val('');
        $('#frm_lesson').find('select').val('').change();
        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
        $('#lesson_contents').html('');
    }
});
</script>

@endpush

@endsection