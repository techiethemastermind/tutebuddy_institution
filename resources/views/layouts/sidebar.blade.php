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
                <li class="sidebar-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">home</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                @can('class_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/class*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.classes.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">Class Management</span>
                    </a>
                </li>
                @endcan

                @can('class_access')
                <li class="sidebar-menu-item {{ Request::is('admin/teacher*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.teachers') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                        <span class="sidebar-menu-text">Teachers</span>
                    </a>
                </li>
                @endcan

                @can('class_access')
                <li class="sidebar-menu-item {{ Request::is('admin/student*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.students') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">school</span>
                        <span class="sidebar-menu-text">Students</span>
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

                    @can('config_access')
                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/general*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.general') }}">
                                <span class="sidebar-menu-text">General</span>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('setting_access')
                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/institution*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.institution') }}">
                                <span class="sidebar-menu-text">Institution</span>
                            </a>
                        </li>
                    </ul>
                    @endcan
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