@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<style type="text/css">
	.profile-avatar img {
	    object-fit: cover;
	    display: block;
	    width: 250px;
	    height: auto;
	    object-position: top;
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
                    <h2 class="mb-0">Edit Institution</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">
                            Edit Institution
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.institutions.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">

            {!! Form::model($institution, ['method' => 'PATCH', 'files' => true, 'route' => ['admin.institutions.update', $institution->id]]) !!}

            <div class="form-group">
                <div class="media">

                	<div class="media-left mr-32pt">
                		<label class="form-label">Logo</label>
                		<div class="profile-avatar mb-16pt">
                			@if($institution->logo)
                			<img src="{{asset('/storage/logos/' . $institution->logo)}}" id="display_logo" alt="people" width="150" class="rounded" />
                			@else
                			<img src="{{asset('/assets/img/logos/no-image.jpg')}}" id="display_logo" alt="people" width="150" class="rounded" />
                			@endif
                		</div>
                		<div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logo_file" data-preview="#display_logo">
                            <label class="custom-file-label" for="logo_file">Choose file</label>
                        </div>
                	</div>

                    <div class="media-body">

				        <div class="page-separator">
				            <div class="page-separator__text">Information</div>
				        </div>
                        <div class="form-group">
			                <label class="form-label">Institution name</label>
			                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'tute-no-empty' => '')) !!}
			            </div>
			            <div class="form-group">
			                <label class="form-label">Institution Tag</label>
			                {!! Form::text('prefix', null, array('placeholder' => 'tag','class' => 'form-control', 'tute-no-empty' => '')) !!}
			            </div>
			            <div class="form-group">
			                <label class="form-label">Admin Password</label>
			                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'tute-no-empty' => '')) !!}
			            </div>
			            <div class="form-group">
			                <label class="form-label">Confirm Password</label>
			                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control', 'tute-no-empty' => '')) !!}
			            </div>
			            <div class="page-separator mt-32pt">
			                <div class="page-separator__text">Contact Information</div>
			            </div>

			            <div class="row">
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">Email Address</label>
			                        {!! Form::text('email', null, array('placeholder' => 'Email', 'class' =>
			                        'form-control', 'tute-no-empty' => '')) !!}
			                    </div>
			                </div>
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">Phone Number</label>
			                        {!! Form::text('phone_number', null, array('placeholder' => 'Phone Number', 'class' =>
			                        'form-control')) !!}
			                    </div>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">Country</label>
			                        {!! Form::text('country', null, array('placeholder' => 'Country', 'class' =>
			                        'form-control')) !!}
			                    </div>
			                </div>
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">State</label>
			                        {!! Form::text('state', null, array('placeholder' => 'State', 'class' =>
			                        'form-control')) !!}
			                    </div>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">City</label>
			                        {!! Form::text('city', null, array('placeholder' => 'City', 'class' =>
			                        'form-control')) !!}
			                    </div>
			                </div>
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">Zip code</label>
			                        {!! Form::text('zip', null, array('placeholder' => 'Zip Code', 'class' =>
			                        'form-control')) !!}
			                    </div>
			                </div>
			            </div>

			            <div class="form-group">
			                <label class="form-label">Address</label>
			                {!! Form::text('address', null, array('placeholder' => 'Address', 'class' =>
			                'form-control')) !!}
			            </div>

			            <div class="row">
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="form-label">Timezone</label>
			                        <select name="timezone" class="form-control"></select>
			                    </div>
			                </div>
			            </div>

			            <div class="form-group text-right">
                            <button id="btn_submit" type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

@include('layouts.parts.sweet-alert')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

	$(function() {

		// Timezone
        $('select[name="timezone"]').timezones();
        $('select[name="timezone"]').val('{{ $institution->timezone }}').change();

        $('#btn_submit').on('click', function(e) {
        	e.preventDefault();

        	$(this).closest('form').ajaxSubmit({
        		success: function(res)
        		{
        			if(res.success) {
        				swal('Success', 'Successfully Updated', 'success');
        			}
        		}
        	});
        })
	});
    
</script>
@endpush