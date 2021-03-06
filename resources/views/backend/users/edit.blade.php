@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

    <style>
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
                    <h2 class="mb-0">Account</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">
                            Edit Account
                        </li>
                    </ol>
                </div>
            </div>

            @if($user->hasRole('Institution Admin') || $user->hasRole('Admin'))
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.admins') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
            @endif

            @if($user->hasRole('Teacher'))
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.teachers') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
            @endif

            @if($user->hasRole('Student'))
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.students') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
            @endif

            <!-- @can('user_create')
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan

            @can('user_delete')
            <div class="row" role="tablist">
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE','route' => ['admin.users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-outline-secondary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endcan -->
        </div>
    </div>

    <div class="page-section container page__container">

        {!! Form::model($user, ['method' => 'PATCH', 'files' => true, 'route' => ['admin.users.update', $user->id]]) !!}

        <div class="">
            <div class="form-group">
                <div class="media">
                    <div class="media-left mr-32pt">
                        <div class="page-separator mb-4">
                            <div class="page-separator__text">User Photo</div>
                        </div>

                        <div class="profile-avatar mb-16pt">
                            @if($user->avatar)
                                <img src="{{ asset('/storage/avatars/' . $user->avatar) }}"
                                    id="user_avatar" alt="people" width="150" class="rounded-circle" />
                            @else
                                <img src="{{ asset('/assets/img/avatars/no-avatar.jpg') }}"
                                    id="user_avatar" alt="people" width="150" class="rounded-circle" />
                            @endif
                        </div>
                        <div>
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                    data-preview="#user_avatar">
                                <label class="custom-file-label" for="avatar_file">Choose file</label>
                            </div>
                        </div>

                    </div>

                    <div class="media-body">

                        <div class="page-separator mb-4">
                            <div class="page-separator__text">User Information</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Institution</label>
                                <select class="form-control" name="institution" id="institions">
                                    @foreach($institutions as $institution)
                                    @if($institution->id == $user->institution->id)
                                    <option value="{{ $institution->id }}" selected>{{ $institution->name }}</option>
                                    @else
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            @if($user->hasRole('Student'))
                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <label class="form-label">Role</label>
                                    {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control', 'disabled'=>true, 'multiple', 'data-toggle'=>'select')) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Roll Number</label>
                                    {!! Form::text('roll_no', null, array('placeholder' => 'Roll Number', 'class' =>
                                    'form-control', 'tute-no-empty' => '')) !!}
                                </div>
                            </div>
                            @else
                            <div class="col-md-8">
                                <div class="form-group mb-24pt">
                                    <label class="form-label">Role</label>
                                    @if(auth()->user()->hasRole('Administrator'))
                                    {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                                    @else
                                    {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control', 'disabled'=>true, 'multiple', 'data-toggle'=>'select')) !!}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    {!! Form::text('first_name', null, array('placeholder' => 'First Name','class' =>
                                    'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Middle Name</label>
                                    {!! Form::text('middle_name', null, array('placeholder' => 'Middle Name','class' =>
                                    'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    {!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    {!! Form::text('email', null, array('placeholder' => 'Email', 'class' =>
                                    'form-control')) !!}
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

                        @if($user->hasRole('Student'))

                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">Parent Information</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" name="father_name" class="form-control" value="{{ $parent->father_name }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Father's Mobile No</label>
                                    <input type="text" name="father_mobile" class="form-control" value="{{ $parent->father_mobile }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Father's Email</label>
                                    <input type="text" name="father_email" class="form-control" value="{{ $parent->father_email }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Mother's Name</label>
                                    <input type="text" name="mother_name" class="form-control" value="{{ $parent->mother_name }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Mother's Mobile No</label>
                                    <input type="text" name="mother_mobile" class="form-control" value="{{ $parent->mother_mobile }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Mother's Email</label>
                                    <input type="text" name="mother_email" class="form-control" value="{{ $parent->mother_email }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Communication Email</label>
                                    <input type="text" name="contact_email" class="form-control" value="{{ $parent->contact_email }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Communication Phone Number</label>
                                    <input type="text" name="contact_phone" class="form-control" value="{{ $parent->contact_phone }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Grade:</label>
                                    {!! Form::select('grade', $grades, $user->grade, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">division:</label>
                                    {!! Form::select('division', $divisions, $user->division, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($user->hasRole('Teacher'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Headline</label>
                                    {!! Form::text('headline', null, array('placeholder' => 'Headline', 'class' =>
                                    'form-control')) !!}
                                </div>

                                <div class="form-group">
                                    <label class="form-label">About you</label>
                                    {!! Form::textarea('about', null, array('placeholder' => 'About You...', 'class' =>
                                    'form-control', 'rows' => 5)) !!}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-24pt">

                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <div class="flex form-inline" style="max-width: 100%">
                                        <label class="form-label" for="user_active">User Status: </label>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" id="user_active" name="status" @if($user->status) checked="" @endif
                                                class="custom-control-input" value="{{ $user->status }} ">
                                            <label class="custom-control-label" for="user_active">&nbsp;</label>
                                        </div>
                                        <label class="form-label mb-0" for="user_active">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <div class="flex form-inline" style="max-width: 100%">
                                        <label class="form-label" for="user_pwd">Change Password: </label>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" id="user_pwd" name="user_pwd" class="custom-control-input">
                                            <label class="custom-control-label" for="user_pwd">&nbsp;</label>
                                        </div>
                                        <label class="form-label mb-0" for="user_pwd">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row wrap d-none" for="user_pwd">
                            <div class="col-md-12">
                                <hr>
                                <div class="wrap">
                                    <div class="form-group">
                                        <div class="controls form-inline">
                                            <label class="form-label col-lg-3">Password:</label>
                                            <input class="form-control col-lg-8" type="password" name="password"
                                                placeholder="New Password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls form-inline">
                                            <label class="form-label col-lg-3">Confirm Password:</label>
                                            <input class="form-control col-lg-8" type="password" name="confirm_password"
                                                placeholder="Confirm Password" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ $user->timezone }}').change();

    $('#avatar_file').on('change', function() {
        var target = $('#user_avatar');
        display_image(this, target);
    });

    $('.custom-checkbox-toggle').on('click', 'input[type="checkbox"]', function() {

        var id = $(this).attr('id');

        if ($(this).prop('checked')) {
            $(this).val('1');
            $('div.wrap[for="' + id + '"').removeClass('d-none');
        } else {
            $(this).val('0');
            $('div.wrap[for="' + id + '"').addClass('d-none');
        }

    });
</script>
@endpush