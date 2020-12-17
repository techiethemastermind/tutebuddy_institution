@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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
            height: 250px;
            object-position: top;
        }
    </style>

@endpush

<?php

if(!isset($_GET["active"])) {
    $_GET["active"] = 'account';
}

?>


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.my_account.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.my_account.title')
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
                            <a href="#account" data-toggle="tab" role="tab" aria-selected="true"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.personal_information')</strong>
                                </span>
                            </a>
                        </div>

                        @if(auth()->user()->hasRole('Teacher'))
                        <div class="col-auto border-left border-right">
                            <a href="#profession" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.personal_information')</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        <div class="col-auto border-left border-right">
                            <a href="#password" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.change_password')</strong>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body tab-content">

                    <!-- Tab Content for Profile Setting -->
                    <div id="account" class="tab-pane p-4 fade text-70 active show">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="media">
                                <div class="media-left mr-32pt">
                                    <label class="form-label">@lang('labels.backend.my_account.your_photo')</label>
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
                                            <label class="custom-file-label" for="avatar_file">@lang('labels.backend.general.choose_file')</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="media-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.profile_name')</label>
                                        {!! Form::text('name', null, array('placeholder' => "@lang('labels.backend.general.name')",'class' =>
                                        'form-control')) !!}
                                        <small class="form-text text-muted">
                                            @lang('string.backend.my_account.profile_name')
                                        </small>
                                    </div>

                                    @if($user->hasRole('Instructor'))

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.headline')</label>
                                        {!! Form::text('headline', null, array('placeholder' => "@lang('labels.backend.my_account.headline')", 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.about')</label>
                                        {!! Form::textarea('about', null, array('placeholder' => "@lang('labels.backend.my_account.about') ...", 'class' =>
                                        'form-control', 'rows' => 5)) !!}
                                    </div>

                                    @endif

                                    <div class="page-separator mt-32pt">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.contact_information')</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.email_address')</label>
                                                {!! Form::text('email', null, array('placeholder' => "@lang('labels.backend.my_account.email_address')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.phone_number')</label>
                                                {!! Form::text('phone_number', null, array('placeholder' => "@lang('labels.backend.my_account.phone_number')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.country')</label>
                                                {!! Form::text('country', null, array('placeholder' => "@lang('labels.backend.my_account.country')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.state')</label>
                                                {!! Form::text('state', null, array('placeholder' => "@lang('labels.backend.my_account.state')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.city')</label>
                                                {!! Form::text('city', null, array('placeholder' => "@lang('labels.backend.my_account.city')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.zip_code')</label>
                                                {!! Form::text('zip', null, array('placeholder' => "@lang('labels.backend.my_account.zip_code')", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.address')</label>
                                        {!! Form::text('address', null, array('placeholder' => "@lang('labels.backend.my_account.address')", 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.timezone')</label>
                                                <select name="timezone" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">@lang('labels.backend.general.save_changes')</button>
                        </div>

                        {!! Form::close() !!}
                    </div>

                    <!-- Tab Content for Professional Information -->
                    <div id="profession" class="tab-pane p-4 fade text-70">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                            ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">
                                            @lang('labels.backend.my_account.profession_certification')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_qualifications" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-qualifications">

                            @if(!empty($user->qualifications))

                                @foreach(json_decode($user->qualifications) as $qualification)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')"
                                        value="{{ $qualification }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.achievement')</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_achievements" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-achievements">

                            @if(!empty($user->achievements))

                                @foreach(json_decode($user->achievements) as $achievement)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')"
                                        value="{{ $achievement }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt col-11">
                            <div class="page-separator">
                                <div class="page-separator__text bg-white">@lang('labels.backend.my_account.experience')</div>
                            </div>
                            {!! Form::textarea('experience', null, array('placeholder' => "@lang('labels.backend.my_account.experience')", 'class' =>
                                'form-control', 'rows' => 5)) !!}
                        </div>

                        {{ Form::close() }}
                    </div>

                    <!-- Tab Content for Profile Setting -->
                    <div id="password" class="tab-pane p-4 fade text-70">
                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group mb-48pt">
                            <label class="form-label" for="current_pwd">@lang('labels.backend.my_account.current_password'):</label>
                            <input id="current_pwd" name="current_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.current_password_placeholder')" tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_pwd">@lang('labels.backend.my_account.new_password'):</label>
                            <input id="new_pwd" name="new_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.new_password_placeholder')" tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cfm_pwd">@lang('labels.backend.my_account.confirm_password'):</label>
                            <input id="cfm_pwd" name="confirm_password" type="password" class="form-control" placeholder="Confirm your new password ..." tute-no-empty>
                        </div>

                        <input type="hidden" name="update_type" value="password">

                        <button type="submit" class="btn btn-primary mt-48pt">@lang('labels.backend.my_account.save_password')</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

    <!-- Timezone Picker -->
    <script src="{{ asset('assets/js/timezones.full.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.js') }}"></script>

    <script>
        $(function () {
            var active_tab = '{{ $_GET["active"] }}';
            $('div[role="tablist"]').find('a').removeClass('active');
            $('div[role="tablist"]').find('a[href="#' + active_tab + '"]').addClass('active');

            $('div.tab-pane').removeClass('show');
            $('div.tab-pane').removeClass('active');
            $('#' + active_tab).addClass('active');
            $('#' + active_tab).addClass('show');

            // Timezone
            $('select[name="timezone"]').timezones();
            if('{{ $user->timezone }}' != '') {
                $('select[name="timezone"]').val('{{ $user->timezone }}').change();
            }

            var tmp = `<div class="row form-inline mb-8pt">
                            <div class="col-10">
                                <input type="text" class="form-control w-100" placeholder="Professional Qualifications and Certifications">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                            </div>
                        </div>`;

            // Add Qualitications
            $('#btn_add_qualifications').on('click', function(e) {
                var row_qualification = $(tmp).clone();
                row_qualification.find('input').attr('name', 'qualification[]');
                row_qualification.appendTo('#profession .wrap-qualifications');
            });

            // Add Achievements
            $('#btn_add_achievements').on('click', function(e) {
                var row_achievement = $(tmp).clone();
                row_achievement.find('input').attr('name', 'achievement[]');
                row_achievement.appendTo('#profession .wrap-achievements');
            });

            $('#profession').on('click', 'button.remove', function(e) {
                $(this).closest('.row').remove();
            });

            $('form').submit(function (e) {
                e.preventDefault();

                $(this).ajaxSubmit({
                    success: function (res) {
                        console.log(res);
                        if (res.success) {
                            swal("Success!", "Successfully updated", "success");
                        }
                    }
                });
            });
        });
    </script>

@endpush

@endsection