<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-light-dodger-blue sidebar-left" data-perfect-scrollbar>

            <a href="" class="sidebar-brand ">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <img src="{{ asset('/assets/img/logos/nav-logo.png') }}" alt="logo" class="img-fluid" />
                </span>
            </a>
            <!-- Sidebar Head -->
            <div class="sidebar-heading">{{ auth()->user()->name }}</div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <!-- Dashboard -->
                <li class="sidebar-menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">home</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->hasRole('Student'))

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#my_study_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                        My Study
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="my_study_menu" style="">

                        <li class="sidebar-menu-item {{ Request::is('admin/my/timetables*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.timetables') }}">
                                <!-- <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">access_time</span> -->
                                <span class="sidebar-menu-text">Timetables</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('admin/my/course*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.courses') }}">
                                <!-- <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span> -->
                                <span class="sidebar-menu-text">Subjects</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('admin/my/live*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.liveSessions') }}">
                                <!-- <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">live_tv</span> -->
                                <span class="sidebar-menu-text">Live Sessions</span>
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#my_task_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                        My Tasks
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="my_task_menu" style="">
                    
                        <li class="sidebar-menu-item {{ Request::is('admin/my/assignment*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.assignments') }}">
                                <span class="sidebar-menu-text">Assignments</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('admin/my/quiz*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.quizs') }}">
                                <span class="sidebar-menu-text">Quizzes</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('admin/my/test*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.tests') }}">
                                <span class="sidebar-menu-text">Tests</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Course Performance (Result) -->
                <li class="sidebar-menu-item {{ Request::is('admin/result*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.results.student') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">poll</span>
                        <span class="sidebar-menu-text">Course Performance</span>
                    </a>
                </li>

                <!-- Cert -->
                <li class="sidebar-menu-item {{ Request::is('admin/certificate*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.certificates.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">new_releases</span>
                        <span class="sidebar-menu-text">My Certifications</span>
                    </a>
                </li>

                <!-- Badges -->
                <li class="sidebar-menu-item {{ Request::is('admin/badges*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.results.student.badges') }}">
                        <i class="material-icons sidebar-menu-icon sidebar-menu-icon--left fa fa-medal"></i>
                        <span class="sidebar-menu-text">My Badges</span>
                    </a>
                </li>
                @endif

                @can('course_access')
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#courses_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        Teach
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="courses_menu" style="">

                        @can('course_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/course*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.courses.index') }}">
                                <span class="sidebar-menu-text">Subjects</span>
                            </a>
                        </li>
                        @endcan

                        @can('schedule_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/schedule*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.schedule') }}">
                                <span class="sidebar-menu-text">Schedule</span>
                            </a>
                        </li>
                        @endcan

                        @if(auth()->user()->hasRole('Teacher'))
                        <li class="sidebar-menu-item {{ Request::is('admin/students*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.students') }}">
                                <span class="sidebar-menu-text">My Students</span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasRole('Teacher'))
                        <li class="sidebar-menu-item {{ Request::is('admin/classes*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.classes.index') }}">
                                <span class="sidebar-menu-text">My Classes</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endcan

                @can('timetable_access')

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#timetable_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">access_time</span>
                        Time Tables
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="timetable_menu">

                        <li class="sidebar-menu-item {{ Request::is('admin/timetables/class*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.timetables.class') }}">
                                <span class="sidebar-menu-text">Class Timetable</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('admin/timetables/exam*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.timetables.exam') }}">
                                <span class="sidebar-menu-text">Exam Timetable</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @endcan

                @can('task_access')
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#task_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                        Tasks
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="task_menu" style="">
                        @can('assignment_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/assignment*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.assignments.index') }}">
                                <span class="sidebar-menu-text">Assignments</span>
                            </a>
                        </li>
                        @endcan

                        @can('test_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/test*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.tests.index') }}">
                                <span class="sidebar-menu-text">Tests</span>
                            </a>
                        </li>
                        @endcan

                        @can('quiz_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/quiz*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.quizs.index') }}">
                                <span class="sidebar-menu-text">Quizzes</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                <!-- Discussion -->
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#community_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people</span>
                        @lang('navs.sidebar.discussion')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="community_menu">
                        <li class="sidebar-menu-item {{ Request::is('admin/discussion*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.my_topics')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('admin/topic*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.topics') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.discussion_topics')</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Messages -->
                <li class="sidebar-menu-item {{ Request::is('admin/message*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.messages.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">send</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.messages')</span>
                    </a>
                </li>

                <!-- My Account -->
                <li class="sidebar-menu-item {{ Request::is('admin/account*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.myaccount') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_circle</span>
                        <span class="sidebar-menu-text">My Account</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Institution Admin') )
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Sidebar Head -->
                <div class="sidebar-heading">My Institution</div>

                <li class="sidebar-menu-item {{ Request::is('admin/admin*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.admins') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people</span>
                        <span class="sidebar-menu-text">Manage Admins</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ Request::is('admin/teacher*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.teachers') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                        <span class="sidebar-menu-text">Manage Teachers</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Request::is('admin/student*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.students') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">school</span>
                        <span class="sidebar-menu-text">Manage Students</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Request::is('admin/schedule/timetable*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.schedule.timetable') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">av_timer</span>
                        <span class="sidebar-menu-text">Manage Timetable</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Request::is('admin/curriculum*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.curriculums.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">Manage Curriculums</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Request::is('admin/class*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.classes.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">Manage Classes</span>
                    </a>
                </li>

                @can('setting_access')
                    <li class="sidebar-menu-item {{ Request::is('admin/settings/institution*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.settings.institution') }}">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_balance</span>
                            <span class="sidebar-menu-text">Setting</span>
                        </a>
                    </li>
                @endcan
            </ul>
            @endif

            @can('system_access')
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Sidebar Head -->
                <div class="sidebar-heading">System</div>

                <!-- Access -->
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#access_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person</span>
                        Access
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="access_menu" style="">

                        @can('user_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/user*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.index') }}">
                                <span class="sidebar-menu-text">All Users</span>
                            </a>
                        </li>
                        @endcan

                        @can('institution_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/institution*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.institutions.index') }}">
                                <span class="sidebar-menu-text">Institutions</span>
                            </a>
                        </li>
                        @endcan

                        @can('role_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/roles*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.roles.index', auth()->user()->prefix) }}">
                                <span class="sidebar-menu-text">Permissions</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#setting_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                        Settings
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        @can('config_access')
                            <li class="sidebar-menu-item {{ Request::is('admin/settings/general*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.settings.general') }}">
                                    <span class="sidebar-menu-text">General</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            </ul>
            @endcan


        </div>
    </div>
</div>
<!-- // END drawer -->

@push('after-scripts')
<script>
    $(document).ready(function(){

        // Make parent menu active
        var active_menus = $('li.sidebar-menu-item.active');
        $.each(active_menus, function(idx, item){
            $(this).closest('ul.sidebar-submenu').parent().addClass('active open');
        });
    });
</script>
@endpush