
    <!--**********************************
        Nav header start
    ***********************************-->
    <div class="nav-header">
        <a href="{{ route('auth.index') }}" class="brand-logo">
            <img class="logo-abbr" src="{{ asset('images/acomma-logo/logo-3.png') }}" alt="">
            <span class="brand-title" style="color: white; font-size: 16px; font-weight: bold;">E-Comma</span>
        </a>

        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>
    <!--**********************************
        Nav header end
    ***********************************-->

    <!--**********************************
        Header start
    ***********************************-->
    <div class="header ">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                            <div class="dropdown-menu p-0 m-0">
                                <form class="form-inline search-full form-inline search" role="search">
                                    <input class="form-control search-form-control" type="search" placeholder="Search" aria-label="Search">
                                </form>
                            </div>
                        </div>
                    </div>

                    <ul class="navbar-nav header-right">
                        <li class="nav-item dropdown language_dropdown">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <i class="mdi mdi-translate"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('locale.switch', ['locale' => 'en', 'redirect_to' => request()->fullUrl()]) }}">English</a>
                                <a class="dropdown-item" href="{{ route('locale.switch', ['locale' => 'vi', 'redirect_to' => request()->fullUrl()]) }}">Tiếng Việt</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown notification_dropdown">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <i class="mdi mdi-bell"></i>

                                @php
                                    $notifications = $notifications ?? (
                                        auth()->check()
                                        ? auth()->user()->notifications()->latest()->limit(5)->get()
                                        : collect()
                                    );
                                @endphp

                                @if($notifications->count())
                                    <span class="badge badge-danger">{{ $notifications->count() }}</span>
                                    <div class="pulse-css"></div>
                                @endif
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                @php
                                    $notifRoutePrefix = 'admin.notifications';
                                @endphp

                                <ul class="list-unstyled">
                                    @forelse ($notifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $badge = $data['badge'] ?? 'primary';
                                        @endphp

                                        <li class="media dropdown-item">
                                            <span class="{{ $badge }}">
                                                <i class="ti-bell"></i>
                                            </span>

                                            <div class="media-body">
                                                <a href="{{ route($notifRoutePrefix . '.read', $notification->id) }}">
                                                    <p>{{ $data['message'] ?? 'New notification' }}</p>
                                                </a>
                                            </div>

                                            <span class="notify-time">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </li>
                                    @empty
                                        <li class="dropdown-item text-center text-muted">
                                            No notifications
                                        </li>
                                    @endforelse
                                </ul>

                                <a class="all-notification" href="{{ route($notifRoutePrefix . '.index') }}">
                                    See all notifications
                                    <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <!-- <span><i class="mdi mdi-account"></i></span> -->
                                <span class="user-avatar">{{ optional(auth()->user())->last_name ?? 'N/A' }} <i class="ti-angle-down f-s-10"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('admin.user.profile', ['user' => auth()->id()]) }}" class="dropdown-item">
                                    <i class="icon-user"></i>
                                    <span class="ml-2">Profile </span>
                                </a>
                                <!-- <a href="./email-inbox.html" class="dropdown-item">
                                    <i class="icon-envelope-open"></i>
                                    <span class="ml-2">Inbox </span>
                                </a> -->
                                <form id="logout-form-navbar" action="{{ route('auth.logout') }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                                <a href="#" class="dropdown-item logout-link" data-confirm-title="Confirm logout" data-confirm-text="Are you sure you want to logout?" data-confirm-button="Logout">
                                    <i class="icon-key"></i>
                                    <span class="ml-2">Logout </span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!--**********************************
        Header end ti-comment-alt
    ***********************************-->
