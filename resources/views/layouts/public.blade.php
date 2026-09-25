<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'Position Evaluation System')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body>

    <header class="public-header">

        <div class="public-header-inner">

            <a
                href="{{ url('/') }}"
                class="public-brand"
            >
                <span class="public-logo">
                    PE
                </span>

                <span>
                    Position Evaluation System
                </span>
            </a>


            <nav class="public-nav">

                <a href="{{ url('/') }}">
                    Home
                </a>

                <a
                    href="{{ route('public.vacancies.index') }}"
                    class="{{ request()->routeIs('public.vacancies.*') ? 'active' : '' }}"
                >
                    Vacancies
                </a>

                @auth

                    <a href="{{ route('dashboard') }}">
                        Management Portal
                    </a>

                @else

                    <a href="{{ route('login') }}">
                        Login
                    </a>

                @endauth

            </nav>

        </div>

    </header>


    <main class="public-content">

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="alert alert-error">
                {{ session('error') }}
            </div>

        @endif

        @yield('content')

    </main>


    <footer class="public-footer">

        <div>

            <strong>
                Position Evaluation & Job Grading Management System
            </strong>

            <p>
                A transparent and systematic platform for position
                evaluation, grading and recruitment.
            </p>

        </div>

        <div>
            © {{ date('Y') }} All rights reserved.
        </div>

    </footer>

</body>

</html>