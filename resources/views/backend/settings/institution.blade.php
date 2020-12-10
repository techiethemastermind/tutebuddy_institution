@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
[dir=ltr] .avatar-2by1 {
    width: 8rem;
    height: 2.5rem;
}

[dir=ltr] label.content-left {
    justify-content: left;
}
[dir=ltr] .avatar-2by1 {
    width: 8rem;
    height: 2.5rem;
}

[dir=ltr] label.content-left {
    justify-content: left;
}

.profile-avatar img {
    object-fit: cover;
    display: block;
    width: 250px;
    height: 250px;
    object-position: top;
}
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Settings</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Settings
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

    	<div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header p-0 nav">
                    <div class="row no-gutters" role="tablist">

                        <div class="col-auto">
                            <a href="#general" data-toggle="tab" role="tab" aria-selected="true"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">General</strong>
                                </span>
                            </a>
                        </div>

                        <div class="col-auto border-left">
                            <a href="#grade" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">Grade or Class</strong>
                                </span>
                            </a>
                        </div>

                        <div class="col-auto border-left">
                            <a href="#division" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">Divisions</strong>
                                </span>
                            </a>
                        </div>

                        <div class="col-auto border-left border-right">
                            <a href="#password" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">Change Password</strong>
                                </span>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="card-body tab-content">

                	<!-- Tab Content for General Setting -->
                    <div id="general" class="tab-pane p-4 fade text-70 active show">

                    	{!! Form::model($institution, ['method' => 'POST', 'files' => true, 'route' => ['admin.settings.institution.save']]) !!}

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
								<div class="form-group">
					                <label class="form-label">Institution Name</label>
					                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'tute-no-empty' => '')) !!}
					            </div>

	                            <div class="page-separator mt-32pt">
					                <div class="page-separator__text bg-white">Contact Information</div>
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

					            <input type="hidden" name="tab" value="general">

					            <div class="form-group text-right">
		                            <button type="submit" class="btn btn-primary">Save changes</button>
		                        </div>
                    		</div>
                    	</div>

                    	{!! Form::close() !!}
                    </div>

                    <!-- Grade Setting for Institution -->
                    <div id="grade" class="tab-pane p-4 fade text-70">

                    	{!! Form::model($institution, ['method' => 'POST', 'files' => true, 'route' => ['admin.settings.institution.save']]) !!}

                    	<div class="form-group">
							<label class="form-label font-size-16pt mb-16pt">Define Grades and Name:</label>
							<div class="custom-controls-stacked form-inline">
								<div class="custom-control custom-radio mr-16pt">
									<input id="rad_grade" name="rad_grade" type="radio" class="custom-control-input" value="Grade" checked="">
									<label for="rad_grade" class="custom-control-label form-label">Grade</label>
								</div>
								<div class="custom-control custom-radio mr-16pt">
									<input id="rad_class" name="rad_grade" type="radio" class="custom-control-input" value="Class">
									<label for="rad_class" class="custom-control-label form-label">Class</label>
								</div>
								<div class="custom-control custom-radio">
									<input id="rad_custom" name="rad_grade" type="radio" class="custom-control-input" value="Custom">
									<label for="rad_custom" class="custom-control-label form-label">Custom</label>
								</div>
							</div>
						</div>

						<hr>
						
						<div class="row">
                    		<div class="col-4 pr-32pt">
		                        <div class="form-group group-wrap" for="rad_grade">
		                        	<label class="form-label font-size-16pt mb-16pt">Grades:</label>
									<div class="grade-group">
										@if(count($institution->classes) > 0)
											@foreach($institution->classes as $class)
												@if($class->type == 'grade')
												<div class="form-group form-inline">
													<label class="form-label mr-8pt" style="width: 80px;">Grade {{ $loop->iteration }}: </label>
													<input type="text" name="grade_name[]" class="form-control flex mr-16pt" value="{{ $class->name }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
												@endif
											@endforeach
										@else
											@for($i = 0; $i < 10; $i++)
												<div class="form-group form-inline">
													<label class="form-label mr-8pt" style="width: 80px;">Grade {{$i+1 }}: </label>
													<input type="text" name="grade_name[]" class="form-control flex mr-16pt" value="Grade {{ $i }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
											@endfor
										@endif
									</div>
									<hr>
									<div class="form-group">
										<button type="button" class="btn btn-outline-secondary btn-block btn-md add">+ Add</button>
									</div>
		                        </div>
                    		</div>

							<div class="col-4 pl-16pt pr-16pt">
		                        <div class="form-group group-wrap" for="rad_grade">
		                        	<label class="form-label font-size-16pt mb-16pt">Junior College:</label>
									<div class="grade-group">
										@if(count($institution->classes) > 0)
											@foreach($institution->classes as $class)
												@if($class->type == 'college')
												<div class="form-group form-inline">
													<label class="form-label">Grade {{ $loop->iteration }}: </label>
													<input type="text" name="college_name[]" class="form-control flex mr-16pt" value="{{ $class->name }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
												@endif
											@endforeach
										@else
											@for($i = 0; $i < 2; $i++)
												<div class="form-group form-inline">
													<label class="form-label">College {{$i+1 }}: </label>
													<input type="text" name="college_name[]" class="form-control flex mr-16pt" value="College {{ $i+1 }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
											@endfor
										@endif
									</div>
									<hr>
									<button type="button" class="btn btn-outline-secondary btn-block btn-md add">+ Add</button>
		                        </div>
                    		</div>

							<div class="col-4 pl-32pt">
		                        <div class="form-group group-wrap" for="rad_grade">
		                        	<label class="form-label font-size-16pt mb-16pt">Graduation:</label>
									<div class="grade-group">
										@if(count($institution->classes) > 0)
											@foreach($institution->classes as $class)
												@if($class->type == 'graduation')
												<div class="form-group form-inline">
													<label class="form-label mb-8pt">Grade {{ $loop->iteration }}: </label>
													<input type="text" name="graduation_name[]" class="form-control flex mr-16pt" value="{{ $class->name }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
												@endif
											@endforeach
										@else
											@for($i = 0; $i < 3; $i++)
												<div class="form-group form-inline">
													<label class="form-label mb-8pt">Graduation {{$i+1 }}: </label>
													<input type="text" name="graduation_name[]" class="form-control flex mr-16pt" value="Graduation {{ $i+1 }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
											@endfor
										@endif
									</div>
									<hr>
									<button type="button" class="btn btn-outline-secondary btn-block btn-md add">+ Add</button>
		                        </div>
                    		</div>
                    	</div>

						<hr>

                    	<input type="hidden" name="tab" value="grade">

                    	<div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>

                    	{!! Form::close() !!}
                    </div>

                    <!-- Division Setting for Institution -->
                    <div id="division" class="tab-pane p-4 fade text-70">

                    	{!! Form::model($institution, ['method' => 'POST', 'route' => ['admin.settings.institution.save']]) !!}

                    	<div class="form-group">
							<label class="form-label font-size-16pt mb-16pt">Define Divisions and Name:</label>
						</div>

						<div class="row">
							<div class="col-5 pr-32pt">
								<div class="form-group group-wrap">
									<div class="division-group">
										@if(count($institution->divisions) > 0)
											@foreach($institution->divisions as $division)
												<div class="form-group form-inline">
													<label class="form-label mr-8pt" style="width: 120px;">Division {{ $loop->iteration }}: </label>
													<input type="text" name="division_name[]" class="form-control flex mr-16pt" value="{{ $division->name }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
											@endforeach
										@else
											@for($i = 0; $i < 3; $i++)
												<div class="form-group form-inline">
													<label class="form-label mr-8pt" style="width: 120px;">Division {{$i+1 }}: </label>
													<input type="text" name="division_name[]" class="form-control flex mr-16pt" value="Division {{ $i }}">
													<button class="btn btn-md btn-outline-secondary remove" type="button"> - </button>
												</div>
											@endfor
										@endif
									</div>
									<hr>
									<button type="button" class="btn btn-outline-secondary btn-block btn-md add">+ Add</button>
								</div>
							</div>
						</div>

						<input type="hidden" name="tab" value="division">

                    	<div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>

						{!! Form::close() !!}
                    </div>

                    <!-- Tab Content for Profile Setting -->
                    <div id="password" class="tab-pane p-4 fade text-70">
                        {!! Form::model($institution, ['method' => 'POST', 'route' => ['admin.settings.institution.save']]) !!}

                        <div class="form-group mb-48pt">
                            <label class="form-label" for="current_pwd">Current Password:</label>
                            <input id="current_pwd" name="current_password" type="password" class="form-control" placeholder="Type current password ..." tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_pwd">New Password:</label>
                            <input id="new_pwd" name="password" type="password" class="form-control" placeholder="Type a new password ..." tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cfm_pwd">Confirm Password:</label>
                            <input id="cfm_pwd" name="confirm_password" type="password" class="form-control" placeholder="Confirm your new password ..." tute-no-empty>
                        </div>

                        <input type="hidden" name="tab" value="password">

                        <button type="submit" class="btn btn-primary mt-48pt">Save password</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

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
		if('{{ $institution->timezone }}' != '') {
			$('select[name="timezone"]').val('{{ $institution->timezone }}').change();
		} else {
			$('select[name="timezone"]').val('UTC').change();
		}

        $('button[type="submit"]').on('click', function(e) {
        	e.preventDefault();

        	$(this).closest('form').ajaxSubmit({
        		success: function(res)
        		{
        			if(res.success) {
        				swal('Success', 'Successfully Updated', 'success');
        			} else {
        				swal('Warning', res.message, 'warning');
        			}
        		}
        	});
        });

        // $('input[name="rad_grade"]').on('click', function() {
        // 	var grade_val = $(this).val();
        // 	var grades = $('div[for="rad_grade"]').find('input[name="grade_name[]"]');
        // 	$.each(grades, function(idx, item) {
        // 		$(item).val(grade_val + ' ' + (idx + 1));
        // 	});
		// 	var colleges = $('div[for="rad_grade"]').find('input[name="college_name[]"]');
        // 	$.each(colleges, function(idx, item) {
        // 		$(item).val(grade_val + ' ' + (idx + 1));
        // 	});
		// 	var graduations = $('div[for="rad_grade"]').find('input[name="graduation_name[]"]');
        // 	$.each(graduations, function(idx, item) {
        // 		$(item).val(grade_val + ' ' + (idx + 1));
        // 	});
        // });

		$('#grade').on('click', 'button.remove', function(e) {
			var form_group = $(this).closest('.form-group');
			form_group.hide('medium', function(){ form_group.remove(); });
		});

		$('#grade').on('click', 'button.add', function(e) {
			var group_wrap = $(this).closest('.group-wrap');
			var form_group = group_wrap.find('div.grade-group').find('div.form-group').last();
			var new_form_group = form_group.clone();
			new_form_group.find('.form-label').text('name:');
			new_form_group.find('input').val('');
			group_wrap.find('div.grade-group').append(new_form_group);
		});

		$('#division').on('click', 'button.add', function(e) {
			var group_wrap = $(this).closest('.group-wrap');
			var form_group = group_wrap.find('div.division-group').find('div.form-group').last();
			var new_form_group = form_group.clone();
			new_form_group.find('.form-label').text('Division :');
			new_form_group.find('input').val('');
			group_wrap.find('div.division-group').append(new_form_group);
		});
	});
    
</script>
@endpush

@endsection