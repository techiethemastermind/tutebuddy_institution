@extends('layouts.app')

@section('content')

@push('after-scripts')

    <style>
        
    </style>

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
            <div class="page-separator__text">Class Timetables</div>
        </div>

        @foreach($classes as $class)

        <div class="media d-table badge-d-table card">

        	<div class="media-left d-table-cell mr-24pt">

        		<div class="avatar avatar-xxl border badge-border">
                    <img src="{{ asset('/assets/img/no-image.jpg') }}" width="300" alt="Badge" class="avatar-img rounded">
                </div>
        	</div>

        	<div class="media-body d-table-cell w-100 border badge-border p-3 align-top">

        		<div class="wrap flex-wrap d-flex">
					<div class="flex flex-column">
						<p class="font-size-16pt mb-0">
	                        <strong>Class: {{ $class->name }}</strong>
	                    </p>
                        
                        <p class="font-size-16pt mb-0">
	                        <strong>Divisions: </strong>
                            @foreach($class->divisions as $division)
                            <span>{{ $division->name }}, </span>
                            @endforeach
	                    </p>
					</div>
					<div class="flex flex-column text-right">
                        @include('layouts.buttons.edit', ['edit_route' => route('admin.timetables.class.edit', $class->id)])
						<button class="btn btn-accent btn-sm"><i class="material-icons">delete</i></button>
					</div>
        		</div>

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

<script>

    $(function() {
        $(document).on('submit', 'form[name="delete_item"]', function(e) {

            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                    } else {
                        swal("Warning!", res.message, "warning");
                    }
                }
            });
        });
    });
</script>
@endpush

@endsection