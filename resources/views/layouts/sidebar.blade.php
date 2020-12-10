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
                                <span class="sidebar-menu-text">Courses</span>
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
                    </ul>
                </li>
                @endcan

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

                @if(auth()->user()->hasRole('Institution Admin') || auth()->user()->hasRole('Teacher'))
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
                @endif
            </ul>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Sidebar Head -->
                <div class="sidebar-heading">User Management</div>

                @if(auth()->user()->hasRole('Administrator'))
                <li class="sidebar-menu-item {{ Request::is('admin/users?role=admin*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.index', ['role' => 'admins']) }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people</span>
                        <span class="sidebar-menu-text">Institution Admins</span>
                    </a>
                </li>
                @endif

                @can('class_access')
                <li class="sidebar-menu-item {{ ( Request::is('admin/users*') && 
                ( isset($_GET['role']) && $_GET['role'] == 'teacher') ) ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.index', ['role' => 'teacher']) }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                        <span class="sidebar-menu-text">Teachers</span>
                    </a>
                </li>
                @endcan

                @can('class_access')
                <li class="sidebar-menu-item {{ ( Request::is('admin/users*') && 
                ( isset($_GET['role']) && $_GET['role'] == 'student') ) ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.index', ['role' => 'student']) }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">school</span>
                        <span class="sidebar-menu-text">Students</span>
                    </a>
                </li>
                @endcan

                @can('class_access')
                <li class="sidebar-menu-item {{ Request::is('admin/class*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.classes.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">Class Management</span>
                    </a>
                </li>
                @endcan
            </ul>

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

                        @can('institution_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/institution*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.institutions.index') }}">
                                <span class="sidebar-menu-text">Institutions</span>
                            </a>
                        </li>
                        @endcan

                        @can('user_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/user*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.index') }}">
                                <span class="sidebar-menu-text">Users</span>
                            </a>
                        </li>
                        @endcan

                        @can('role_access')
                        <li class="sidebar-menu-item {{ Request::is('admin/roles*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.roles.index', auth()->user()->prefix) }}">
                                <span class="sidebar-menu-text">Roles</span>
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

                        @if(auth()->user()->hasRole('Institution Admin'))
                            @can('setting_access')
                                <li class="sidebar-menu-item {{ Request::is('admin/settings/institution*') ? 'active' : '' }}">
                                    <a class="sidebar-menu-button" href="{{ route('admin.settings.institution') }}">
                                        <span class="sidebar-menu-text">Institution</span>
                                    </a>
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
            
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