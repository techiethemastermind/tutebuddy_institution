<!-- Header -->

<div id="header" class="mdk-header js-mdk-header mb-0" data-fixed data-effects="" @if(!auth()->check()) style="display: none;" @endif>
    <div class="mdk-header__content">
        <div class="navbar navbar-expand navbar-light navbar-light-dodger-blue navbar-shadow" id="default-navbar" data-primary>

            <!-- Navbar toggler -->
            <button class="navbar-toggler w-auto mr-16pt d-block rounded-0" type="button" data-toggle="sidebar">
                <span class="material-icons">short_text</span>
            </button>

            <!-- Navbar Brand -->
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand mr-16pt">

                <span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">
                    <img src="{{ asset('/assets/img/logos/tutebuddy-logo-full.png') }}" alt="logo" class="img-fluid" />
                </span>
            </a>

            <div class="flex"></div>

            <div class="nav navbar-nav flex-nowrap d-flex mr-16pt">

                <!-- Notifications dropdown -->
                <div class="nav-item ml-16pt dropdown dropdown-notifications dropdown-xs-down-full"
                    data-toggle="tooltip" data-title="Notifications" data-placement="bottom"
                    data-boundary="window">
                    <button class="nav-link btn-flush dropdown-toggle" type="button" data-toggle="dropdown"
                        data-caret="false">
                        <i class="material-icons">notifications_none</i>
                        <span class="badge badge-notifications badge-accent">2</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div data-perfect-scrollbar class="position-relative">
                            <div class="dropdown-header"><strong>System notifications</strong></div>
                            <div class="list-group list-group-flush mb-0">

                                <a href="javascript:void(0);"
                                    class="list-group-item list-group-item-action unread">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">3 minutes ago</small>

                                        <span class="ml-auto unread-indicator bg-accent"></span>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-accent">account_circle</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">

                                            <span class="text-black-70">Your profile information has not been
                                                synced correctly.</span>
                                        </span>
                                    </span>
                                </a>

                                <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">5 hours ago</small>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-primary">group_add</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="text-black-100">Adrian. D</strong>
                                            <span class="text-black-70">Wants to join your private group.</span>
                                        </span>
                                    </span>
                                </a>

                                <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">1 day ago</small>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-warning">storage</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">

                                            <span class="text-black-70">Your deploy was successful.</span>
                                        </span>
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- // END Notifications dropdown -->

                @if(auth()->check())
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center dropdown-toggle"
                        data-toggle="dropdown" data-caret="false">

                        <span class="avatar avatar-sm mr-8pt2">

                        @if(!empty(auth()->user()->avatar))
                        <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar) }}" alt="people" class="avatar-img rounded-circle">
                        @else
                        <span class="avatar-title rounded-circle">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        @endif

                        </span>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->name }}</strong>
                        </div>
                        <a class="dropdown-item" href="{{ route('admin.settings.institution') }}">My Account</a>
                        <a class="dropdown-item" href="">Help Center</a>
                        <a class="dropdown-item" href="">Forum</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
