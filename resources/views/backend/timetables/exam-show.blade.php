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
                    <h2 class="mb-0">Timetable for {{ $class->name }}</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Edit Timetable
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.timetables.class') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">

        @foreach($class->examTimeTables() as $timetable)
        <div class="page-separator">
            <div class="page-separator__text">Division {{ $timetable->name }}</div>
        </div>

        <div class="card card-body">
        	
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
        </div>

        @endforeach
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