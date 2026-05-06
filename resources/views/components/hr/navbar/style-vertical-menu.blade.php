
    <!--**********************************
        Nav header start
    ***********************************-->
    <div class="nav-header">
        <a href="{{ route('auth.index') }}" class="brand-logo">
            <img class="logo-abbr" src="{{ asset('images/acomma-logo/logo-4.png') }}" alt="">
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

    <style>
        .notification-count { position: absolute; top: -6px; right: -6px; font-size: 10px; padding: 2px 5px; border-radius: 10px; }
        .notification-link { display: flex; align-items: center; text-decoration: none; color: inherit; padding: .5rem .75rem; }
        .notification-link .media-body { flex: 1; }
        .notification-link .notify-time { margin-left: .5rem; white-space: nowrap; }
        .notification-link:hover { background: rgba(0,0,0,0.03); }
    </style>

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
                        @php
                            $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                            $notifications = $notifications ?? (
                                auth()->check() ? auth()->user()->notifications()->latest()->take(5)->get() : collect()
                            );
                        @endphp

                        <li class="nav-item dropdown notification_dropdown">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <span class="notification-icon">
                                    <i class="mdi mdi-bell"></i>
                                    @if($unreadCount)
                                        <div class="pulse-css"></div>
                                    @endif
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="list-unstyled">
                                    @forelse($notifications as $note)
                                        <a href="{{ $note->data['url'] ?? '#' }}"
                                           class="media dropdown-item notification-link"
                                           data-url="{{ $note->data['url'] ?? '' }}"
                                           data-action-url="{{ $note->data['action_url'] ?? '' }}"
                                           data-notification-id="{{ $note->id }}">
                                            <span class="primary"><i class="ti-bell"></i></span>
                                            <div class="media-body">
                                                <p>{!! 
                                                    \Illuminate\Support\Str::limit(
                                                        $note->data['message'] ?? ($note->data['title'] ?? null) ?? ($note->data ?? json_encode($note->data)),
                                                        120
                                                    )
                                                !!}</p>
                                            </div>
                                            <span class="notify-time">{{ optional($note->created_at)->diffForHumans() }}</span>
                                        </a>
                                    @empty
                                        <li class="dropdown-item text-center">No notifications</li>
                                    @endforelse
                                </ul>
                                <a type="button" class="all-notification btn btn-link p-0" data-toggle="modal" data-target="#allNotificationsModal">
                                    See all notifications 
                                    <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </li>

                        @php
                            $allNotifications = auth()->check() ? auth()->user()->notifications()->latest()->get() : collect();
                            $clearRoute = route('staff.notifications.clear-all');
                        @endphp

                        <!-- All Notifications Modal -->
                        <div class="modal fade" id="allNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="list-group">
                                            @forelse($allNotifications as $notification)
                                                @php $d = $notification->data; @endphp
                                                <div class="list-group-item notification-item {{ $notification->read_at ? 'read' : 'unread' }}">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ $d['message'] ?? 'Notification' }}</h6>
                                                        <small class="text-muted">{{ optional($notification->created_at)->diffForHumans() }}</small>
                                                    </div>
                                                    @if(!empty($d['action_url']))
                                                        <p class="mb-1"><a href="{{ $d['action_url'] }}">Open</a></p>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="text-center text-muted">No notifications</div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" id="clearNotificationsBtn" class="btn btn-danger">Clear all</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <!-- <i class="mdi mdi-account"></i> -->
                                 <span class="user-avatar">{{ optional(auth()->user())->last_name ?? 'N/A' }} <i class="ti-angle-down f-s-10"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('hr.user.profile', ['user' => auth()->id()]) }}" class="dropdown-item">
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
                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allNotificationsModal = document.getElementById('allNotificationsModal');
            if (allNotificationsModal && allNotificationsModal.parentNode !== document.body) {
                document.body.appendChild(allNotificationsModal);
            }

            const clearBtn = document.getElementById('clearNotificationsBtn');
            if (!clearBtn) return;
            clearBtn.addEventListener('click', function () {
                if (!confirm('Clear all notifications?')) return;
                fetch("{{ $clearRoute }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(r => r.json()).then(json => {
                    if (json.success) {
                        document.querySelectorAll('#allNotificationsModal .notification-item').forEach(n => n.remove());
                        const pulse = document.querySelector('.pulse-css');
                        if (pulse) pulse.remove();
                        $('#allNotificationsModal').modal('hide');
                    } else {
                        alert('Failed to clear notifications');
                    }
                }).catch(() => alert('Failed to clear notifications'));
            });

            document.addEventListener('click', function (ev) {
                const a = ev.target.closest && ev.target.closest('.notification-link');
                if (!a) return;
                const href = a.getAttribute('href') || a.dataset.url || '';
                const actionUrl = a.dataset.actionUrl || '';
                const id = a.dataset.notificationId || a.dataset.notificationid || '';

                ev.preventDefault();
                ev.stopPropagation();

                if (href && href !== '#') {
                    window.location.href = href;
                    return;
                }

                if (actionUrl) {
                    window.location.href = actionUrl;
                    return;
                }

                if (id) {
                    window.location.href = '/notifications/' + id;
                    return;
                }
            });
        });
    </script>
