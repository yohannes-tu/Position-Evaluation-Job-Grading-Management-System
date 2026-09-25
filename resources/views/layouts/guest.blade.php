<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <div class="auth-shell">
            <aside class="auth-intro">
                <a href="/" class="auth-brand">
                    <span class="auth-brand-mark">
                        <x-application-logo />
                    </span>
                    <span>
                        <strong>Position Evaluation</strong>
                        <small>Talent decisions, made clear.</small>
                    </span>
                </a>

                <div class="auth-intro-copy">
                    <p class="auth-kicker">Recruitment workspace</p>
                    <h1>Build better teams with confidence.</h1>
                    <p>Evaluate roles, compare candidates, and keep every hiring decision grounded in a clear process.</p>
                </div>

                <div class="auth-intro-footer">
                    <span class="auth-signal"></span>
                    <span>Secure access for your evaluation team</span>
                </div>
            </aside>

            <main class="auth-main">
                <div class="auth-card">
                    <div class="auth-heading">
                        <p class="auth-kicker">{{ request()->routeIs('register') ? 'Get started' : 'Welcome back' }}</p>
                        <h2>{{ request()->routeIs('register') ? 'Create your account' : 'Sign in to your workspace' }}</h2>
                        <p>{{ request()->routeIs('register') ? 'Set up your profile to begin evaluating positions.' : 'Enter your details to continue where you left off.' }}</p>
                    </div>

                    {{ $slot }}
                </div>
            </div>
            </main>
        </div>
    </body>
</html>
