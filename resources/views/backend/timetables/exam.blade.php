@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Nestable CSS -->
<link type="text/css" href="{{ asset('assets/css/nestable.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Timetables</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Timetables
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Exam Timetables</div>
        </div>

        @foreach($classes as $class)

        <div class="class-item card">
            <a href="#" class="accordion__toggle" data-toggle="collapse" data-target="#lesson-toc-79" data-parent="#parent" aria-expanded="true">
                <span class="flex">{{ $class->name }}</span>
                <span class="accordion__toggle-icon material-icons class-edit mr-16pt" 
                    data-route="{{ route('admin.timetables.exam.show', $class->id) }}">remove_red_eye</span>
                <span class="accordion__toggle-icon material-icons class-edit" 
                    data-route="{{ route('admin.timetables.exam.edit', $class->id) }}">edit</span>
            </a>
            <div class="nestable pl-12pt pr-12pt pb-12pt">
                <ul class="nestable-list">
                    @foreach($class->examTimeTables() as $timetable)
                    <li class="nestable-item" data-id="{{ $timetable->id }}">
                        <div class="nestable-handle">
                            <div class="accordion__menu-link p-0">
                                <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                <span class="flex" data-order="{{ $loop->iteration }}">
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($timetable->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project"><strong>{{ $timetable->name }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $timetable->url }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @endforeach

        <div class="card card-footer p-8pt">
            @if($classes->hasPages())
            {{ $classes->links('layouts.parts.page') }}
            @else
            <ul class="pagination justify-content-start pagination-xsm m-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                        <span>Prev</span>
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Page 1">
                        <span>1</span>
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                        <span>Next</span>
                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/js/jquery.nestable.js') }}"></script>
<script src="{{ asset('assets/js/nestable.js') }}"></script>

<script>
    $(function() {
        $('.nestable').on('change', function() {

            var order_json = $(this).nestable('serialize');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: "{{ route('admin.ajax.timetables.order') }}",
                method: 'POST',
                data: {data: order_json},
                success: function(res) {
                    console.log(res);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('span.class-edit').on('click', function() {
            window.location.href = $(this).attr('data-route');
        });
    });
</script>

@endpush

@endsection