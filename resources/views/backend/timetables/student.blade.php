@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    [dir=ltr] .media.embeded embed {
        height: 80vh;
    }

    [dir=ltr] .media.embeded img {
        height: 80vh;
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
                    <h2 class="mb-0">My Timetables</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item active">
                            My Timetable
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">

        <div class="flex" style="max-width: 100%">
            <div class="card mb-0">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#class">Class Timetable</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#exam">Exam Timetables</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3 text-70">
                        <div class="tab-pane active" id="class">
                            <div class="media embeded">
                                @if($classTimetable)
                                    @if($classTimetable->type != 'pdf')
                                    <img src="{{ asset('/storage/attachments/' . $classTimetable->url) }}"
                                        alt="people" width="100%" class="rounded" />

                                    <embed src="{{ asset('/storage/attachments/' . $classTimetable->url) }}" type="application/pdf"
                                        alt="people" width="100%" class="rounded" style="display:none;" />

                                    @else
                                    <img src="{{ asset('/storage/attachments/' . $classTimetable->url) }}"
                                        alt="people" width="100%" class="rounded" style="display:none;" />

                                    <embed src="{{ asset('/storage/attachments/' . $classTimetable->url) }}" type="application/pdf"
                                        alt="people" width="100%" class="rounded" />
                                    @endif
                                @else
                                <img src="{{ asset('/assets/img/no-image.jpg') }}" alt="people" width="100%" class="rounded" />

                                <embed src="{{ asset('/assets/img/no-image.jpg') }}" type="application/pdf" alt="people" width="100%" class="rounded" style="display:none;" />
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane" id="exam">
                            @foreach($examTimetables as $timetable)
                            <div class="page-separator mt-32pt">
                                <div class="page-separator__text bg-white">Division {{ $timetable->name }}</div>
                            </div>

                            <div class="media embeded">
                                @if($timetable->type != 'pdf')
                                <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                                    alt="people" width="100%" class="rounded" />

                                <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                                    alt="people" width="100%" class="rounded" style="display:none;" />

                                @else
                                <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                                    alt="people" width="100%" class="rounded" style="display:none;" />

                                <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                                    alt="people" width="100%" class="rounded" />
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    $(function() {
        // Script
    });
</script>
@endpush

@endsection