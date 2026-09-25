<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Position Evaluation System')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>

    <div class="app-container">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main area --}}
        <div class="main-area">

            {{-- Topbar --}}
            @include('layouts.topbar')

            {{-- Page content --}}
            <main class="page-content">
                @yield('content')
            </main>

        </div>
        

    </div>

    @stack('scripts')

</body>
</html>