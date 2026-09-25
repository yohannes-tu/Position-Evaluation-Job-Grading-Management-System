<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <div class="brand-icon">
            PE
        </div>

        <div class="brand-text">
            <h2>Position Evaluation</h2>
            <span>Management System</span>
        </div>
    </div>

    <nav class="sidebar-nav">

        {{-- MAIN --}}
        <div class="nav-section">
            <span class="nav-section-title">MAIN</span>

            <a href="{{ route('public.landing') }}"
               class="nav-item">
                <span class="nav-icon">⌂</span>
                <span>Home</span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">⌂</span>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role === 'public_user')
                <a href="{{ route('public.vacancies.index') }}"
                   class="nav-item {{ request()->routeIs('public.vacancies.*') ? 'active' : '' }}">
                    <span class="nav-icon">▣</span>
                    <span>Published Vacancies</span>
                </a>
            @endif
        </div>


        @if(auth()->user()->role !== 'public_user')

        {{-- JOB MANAGEMENT --}}
        <div class="nav-section">
            <span class="nav-section-title">JOB MANAGEMENT</span>

            <a href="{{ route('departments.index') }}"
               class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <span class="nav-icon">▣</span>
                <span>Departments</span>
            </a>

            <a href="{{ route('positions.index') }}"
               class="nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                <span class="nav-icon">▤</span>
                <span>Positions</span>
            </a>

            <a href="{{ route('evaluation-factors.index') }}"
               class="nav-item {{ request()->routeIs('evaluation-factors.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙</span>
                <span>Evaluation Factors</span>
            </a>

            <a href="{{ route('salary-scales.index') }}"
               class="nav-item {{ request()->routeIs('salary-scales.*') ? 'active' : '' }}">
                <span class="nav-icon">$</span>
                <span>Salary Scales</span>
            </a>

            <a href="#"
               class="nav-item">
                <span class="nav-icon">✓</span>
                <span>Evaluations</span>
            </a>

             <a href="{{ route('position-rankings.index') }}"
               class="nav-item {{ request()->routeIs('position-rankings.*') ? 'active' : '' }}">
                <span class="nav-icon">🏆</span>
                <span>Position Rankings</span>
            </a>

             <div class="nav-section">

    <span class="nav-section-title">
        RECRUITMENT
    </span>

    <a
    href="{{ route('recruitment.dashboard') }}"
    class="nav-item {{ request()->routeIs('recruitment.dashboard') ? 'active' : '' }}"
>

    <span class="nav-icon">
        ▦
    </span>

    <span>
        Recruitment Dashboard
    </span>

</a>


    <a
        href="{{ route('vacancies.index') }}"
        class="nav-item {{ request()->routeIs('vacancies.*') ? 'active' : '' }}"
    >

        <span class="nav-icon">
            ▣
        </span>

        <span>
            Vacancies
        </span>

    </a>


    <a
        href="{{ route('applications.index') }}"
        class="nav-item {{ request()->routeIs('applications.*') ? 'active' : '' }}"
    >

        <span class="nav-icon">
            ◉
        </span>

        <span>
            Applications
        </span>

    </a>

    <a
    href="{{ route('interviews.index') }}"
    class="nav-item {{ request()->routeIs('interviews.*') ? 'active' : '' }}"
>
    <span class="nav-icon">◉</span>
    <span>Interviews</span>
</a>
<a
    href="{{ route('recruitment.reports') }}"
    class="nav-item {{ request()->routeIs('recruitment.reports*') ? 'active' : '' }}"
>
    <span class="nav-icon">▤</span>
    <span>Recruitment Reports</span>
</a>
    

</div>
            
        
        </div>


        {{-- REPORTING --}}
        <div class="nav-section">
            <span class="nav-section-title">REPORTING</span>

           

                <a href="{{ route('reports.index') }}"
                    class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="nav-icon">▥</span>
                <span>Reports</span>
            </a>

        </div>


        {{-- ADMINISTRATION --}}
        @if(auth()->user()->role === 'admin')

            <div class="nav-section">
                <span class="nav-section-title">ADMINISTRATION</span>

                <a href="#"
                   class="nav-item">
                    <span class="nav-icon">♙</span>
                    <span>Users</span>
                </a>

                <a href="#"
                   class="nav-item">
                    <span class="nav-icon">⚙</span>
                    <span>Settings</span>
                </a>
            </div>

        @endif

        @endif

    </nav>


    {{-- LOGOUT --}}
    <div class="sidebar-footer">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-button">
                <span class="nav-icon">↪</span>
                <span>Logout</span>
            </button>
        </form>

    </div>

</aside>