<header class="topbar">

    <div class="topbar-left">

        <button
            type="button"
            class="menu-button"
            onclick="toggleSidebar()"
            aria-label="Toggle sidebar">
            ☰
        </button>

        <div>
            <h1>@yield('page-title', 'Dashboard')</h1>
            <p>Position Evaluation System</p>
        </div>

    </div>

    <div class="topbar-right">

        <a
            href="{{ route('notifications.index') }}"
            class="topbar-notification-link"
        >
            Notifications

            @if(auth()->user()->unreadNotifications->count())
                <span class="notification-count">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>

        <div class="user-info">

            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="user-details">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ ucfirst(auth()->user()->role) }}</span>
            </div>

        </div>

    </div>

</header>