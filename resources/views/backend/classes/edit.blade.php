@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Class</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Edit Class
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
    	<div class="page-separator">
            <div class="page-separator__text">Class Information</div>
        </div>

        <div class="col-md-6 p-0">
            
        	{!! Form::model($class, ['method' => 'PATCH', 'route' => ['admin.classes.update', $class->id]]) !!}

                <div class="form-group">
                    <label class="form-label">Class Name: </label>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' =>'form-control')) !!}
                </div>

                <div class="form-group mb-24pt">
                    <label class="form-label">Subjects: </label>
                    {!! Form::select('course[]', $courses, $class->courses, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                </div>

                <div class="form-group mb-24pt">
                    <label class="form-label">Divisions: </label>
                    {!! Form::select('division[]', $divisions, $class->divisions, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                </div>

                <div class="form-group mb-24pt">
                    <label class="form-label">students: </label>
                    {!! Form::select('students[]', $students, $class->students, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                </div>
                
                <button class="btn btn-primary">Save changes</button>
            
            {!! Form::close() !!}
        </div>
    </div>

</div>

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script type="text/javascript">

	$(function() {

		//
	});
</script>

@endpush

@endsection