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
                    @if(!auth()->check())
                    <img src="{{ asset('/assets/img/logos/tutebuddy-logo-full.png') }}" alt="logo" class="img-fluid" />
                    @else
                        @if(!empty(auth()->user()->institution->logo))
                        <img src="{{ asset('/storage/logos/' . auth()->user()->institution->logo) }}" class="img-fluid" alt="logo">
                        @else
                        <img src="{{ asset('/assets/img/logos/tutebuddy-logo-full.png') }}" alt="logo" class="img-fluid" />
                        @endif
                    @endif
                </span>
            </a>

            <div class="flex"></div>

            <div class="nav navbar-nav flex-nowrap d-flex mr-16pt">

                @if(auth()->check())
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center dropdown-toggle"
                        data-toggle="dropdown" data-caret="false">

                        <span class="avatar avatar-sm mr-8pt2">

                            @if(!empty(auth()->user()->avatar))
                            <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar) }}" alt="people" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ substr(auth()->user()->first_name, 0, 2) }}</span>
                            @endif

                        </span>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->user_name }}</strong>
                        </div>
                        <a class="dropdown-item" href="{{ route('admin.myaccount') }}">My Account</a>
                        <!-- <a class="dropdown-item" href="">Help Center</a>
                        <a class="dropdown-item" href="">Forum</a> -->
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
