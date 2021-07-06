@extends('layouts.app')

@push('after-styles')

<!-- Full Calendar -->
<link type="text/css" href="{{ asset('assets/plugin/fullcalendar-scheduler/main.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

@endpush

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Schedule</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Schedule
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
            <div class="card p-4">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule New Modal -->
<div class="modal fade" id="timetableModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label class="form-label">Start Time:</label>
                                <input id="start_time" type="time" class="form-control"
                                    placeholder="Pick start time" value="">
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label class="form-label">End Time:</label>
                                <input id="end_time" type="time" class="form-control" placeholder="Pick end time"
                                    value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label for="" class="form-label">Detail:</label>
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="" class="form-label d-block">Teachers: </label>
                                <select id="select_teachers" name="teachers" class="form-control">
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->fullName() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label mr-3">Timezone: </label>
                                <select id="d_timezone" name="timezone" class="form-control"></select>
                            </div>

                            <div class="form-group mb-0">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <div class="form-group">
                                            <label class="form-label d-block">Classes:</label>
                                            <select id="select_classes" name="classes" class="form-control">
                                                @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group">
                                            <label class="form-label d-block">Divisions:</label>
                                            <select id="select_divisions" name="divisions" class="form-control">
                                                <option value="A">A</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button class="btn btn-outline-primary btn-add-new">Add New</button>
                    <button class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')

<!-- Full Calendar -->
<script src="{{ asset('assets/plugin/fullcalendar-scheduler/main.js') }}"></script>

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

    var select_teachers = $('#select_teachers').select2({width: '100%'});
    var select_classes = $('#select_classes').select2({width: '100%'});
    var select_divisions = $('#select_divisions').select2({width: '100%'});
    var select_timezone = $('#d_timezone').select2({width: '100%'});

    var schedule_startStr, schedule_endStr, schedule_startTime, schedule_endTime, schedule_id;

    var schedule_data = $('#schedule_data').val();
    var my_timezone = '{{ auth()->user()->timezone }}';

    $('#d_timezone').timezones();

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: false,
        initialView: 'timeGridWeek',
        dayHeaderFormat:  { weekday: 'long' },
        timeZone: 'UTC',
        allDaySlot: false,
        slotMinTime: '06:00:00',
        slotDuration: '01:00',
        slotLabelContent: function(info) {
            var hour = parseInt(info.text.substring(0, info.text.length - 2));
            var surfix = info.text.substring(info.text.length - 2);
            var from = hour, to = hour + 1;
            if(from < 10) {
                from = '0' + from;
            }
            if(to < 10) {
                to = '0' + to;
            }
            return {html: `<strong class="px-2">` + from + ':00 - ' + to + ':00' + `</strong>`};
        },
        selectable: true,
        eventSources: [{
            url: '{{ route("admin.getTimetableData") }}',
            editable: true,
            success: function(content, xhr) {
                return content.data;
            }
        }],
        eventContent: function(info) {
            var html = `<div class="fc-event-title-container">
                        <div class="fc-event-title">` + info.event.title + `</div>
                    </div>`;
            return { html: html};
        },
        eventResize: function(info) {
            schedule_id = info.event.id;
            schedule_startStr = info.event.startStr;
            schedule_endStr = info.event.endStr;
        },
        eventClick: function(info) {

            schedule_id = info.event.id;
            schedule_startStr = info.event.startStr;
            schedule_startTime = ("0" + info.event.start.getUTCHours()).slice(-2) + ':' + ("0" + info.event.start.getUTCMinutes()).slice(-2);
            schedule_endStr = info.event.endStr;
            schedule_endTime = ("0" + info.event.end.getUTCHours()).slice(-2) + ':' + ("0" + info.event.end.getUTCMinutes()).slice(-2);

            // Get Timetable by selected schedule ID
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.getTimetableById') }}",
                data: {id: schedule_id},
                success: function(res) {
                    if (res.success) {
                        display_detail(res.schedule);
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        },
        select: function(info) {

            schedule_startStr = info.startStr;
            schedule_startTime = ("0" + info.start.getUTCHours()).slice(-2) + ':' + ("0" + info.start.getUTCMinutes()).slice(-2);
            schedule_endStr = info.endStr;
            schedule_endTime = ("0" + info.end.getUTCHours()).slice(-2) + ':' + ("0" + info.end.getUTCMinutes()).slice(-2);

            display_detail();
        },
        eventDrop: function(info) {
            schedule_id = info.event.id;
            schedule_startStr = info.event.startStr;
            schedule_endStr = info.event.endStr;
        }
    });
    calendar.render();

    // Ajax header for Ajax POST
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Add new schedule Event
    $('#timetableModal').on('click', '.btn-add-new', function(e) {

        var teacher_id = $('select[name="teachers"]').val();
        var class_id = $('select[name="classes"]').val();
        var division_id = $('select[name="divisions"]').val();

        // Add new timetable schedule
        var send_data = {
            teacher_id: teacher_id,
            class_id: class_id,
            division_id: division_id,
            time: schedule_startStr,
            timezone:$('#d_timezone').val()
        };

        $.ajax({
            method: 'POST',
            url: "{{ route('admin.storeTimeTableData') }}",
            data: send_data,
            success: function(res) {

                if (res.success) {
                    calendar.refetchEvents();
                    $('#timetableModal').modal('toggle');
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                var alert = getAlert('Error', errMsg, 'error');
                $('#timetableModal .modal-body').prepend(alert);
            }
        });
    });

    // Get division by ClassId
    select_classes.on('change', function(e) {

        var class_id = $(this).val();

        if(class_id !== null) {
            // get divisions by class id
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.getDivisionsById') }}",
                data: {id: class_id},
                success: function(res) {
                    if (res.success) {
                        select_divisions.html($(res.html)).select2();
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        }
        
    });

    // Display details for Course modal
    function display_detail(data = null) {
        $('#start_time').val(schedule_startTime);
        $('#end_time').val(schedule_endTime);

        if(my_timezone != '') {
        	$('#d_timezone').val(my_timezone).change();
        }

        if(data !== null) {
            select_teachers.val(data.user_id).change();
            select_classes.val(data.grade_id).change();
            select_divisions.val(data.division_id).change();
        }

        $('#timetableModal').modal('toggle');
    }

    $('span.event-remove').on('click', function(e) {
        e.preventDefault();
    });

});
</script>

@endpush