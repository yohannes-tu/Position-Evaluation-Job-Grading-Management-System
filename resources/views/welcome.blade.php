<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name', 'PEJGMS') }} |
        Position Evaluation & Job Grading Management System
    </title>

    <meta
        name="description"
        content="A transparent and systematic platform for evaluating organizational positions, determining job grades, managing salary structures, and supporting fair recruitment."
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="landing-page">

    <!-- Navigation -->
    <header class="site-header">
        <div class="container nav-container">

            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <span class="brand-copy">
                    <strong>PEJGMS</strong>
                    <small>Position Evaluation System</small>
                </span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="#home" class="active">Home</a>
                <a href="#about">About</a>
                <a href="#methodology">Methodology</a>
                <a href="#workflow">Workflow</a>
                <a href="#vacancies">Vacancies</a>
                <a href="#contact">Contact</a>
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        Dashboard
                    </a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="login-link">
                            Sign in
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Get started
                        </a>
                    @endif
                @endauth
            </div>

            <button
                class="mobile-menu-button"
                type="button"
                aria-label="Open navigation menu"
                aria-expanded="false"
                onclick="toggleMobileMenu()"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

        <div class="mobile-nav" id="mobileNav">
            <a href="#home" onclick="closeMobileMenu()">Home</a>
            <a href="#about" onclick="closeMobileMenu()">About</a>
            <a href="#methodology" onclick="closeMobileMenu()">Methodology</a>
            <a href="#workflow" onclick="closeMobileMenu()">Workflow</a>
            <a href="#vacancies" onclick="closeMobileMenu()">Vacancies</a>
            <a href="#contact" onclick="closeMobileMenu()">Contact</a>

            @auth
                <a href="{{ url('/dashboard') }}" onclick="closeMobileMenu()">
                    Dashboard
                </a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" onclick="closeMobileMenu()">
                        Sign in
                    </a>
                @endif
            @endauth
        </div>
    </header>


    <!-- Hero Section -->
    <main>

        <section class="hero-section" id="home">
            <div class="container hero-grid">

                <div class="hero-content">

                    <div class="eyebrow">
                        <span class="eyebrow-dot"></span>
                        Modern workforce management
                    </div>

                    <h1>
                        Build a fairer,
                        <span>stronger</span>
                        organization.
                    </h1>

                    <p class="hero-description">
                        A transparent and systematic platform for evaluating
                        organizational positions, determining job grades,
                        managing salary structures, and supporting fair
                        recruitment.
                    </p>

                    <div class="hero-actions">
                        <a href="#methodology" class="btn btn-primary btn-large">
                            Explore the system
                            <span class="arrow">→</span>
                        </a>

                        <a href="#workflow" class="text-button">
                            See how it works
                            <span>↗</span>
                        </a>
                    </div>

                    <div class="hero-trust">
                        <div class="trust-avatars">
                            <span>HR</span>
                            <span>EV</span>
                            <span>CM</span>
                        </div>

                        <div>
                            <strong>Designed for modern institutions</strong>
                            <p>HR teams, committees, administrators, and recruiters</p>
                        </div>
                    </div>

                </div>


                <!-- Dashboard Preview -->
                <div class="hero-visual">

                    <div class="visual-glow"></div>

                    <div class="dashboard-window">

                        <div class="window-header">
                            <div class="window-brand">
                                <span class="mini-brand-mark">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>

                                <span>PEJGMS Workspace</span>
                            </div>

                            <div class="window-controls">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>

                        <div class="window-body">

                            <div class="preview-sidebar">
                                <div class="preview-sidebar-title">
                                    MANAGEMENT
                                </div>

                                <div class="preview-sidebar-item active">
                                    <span>▦</span>
                                    Dashboard
                                </div>

                                <div class="preview-sidebar-item">
                                    <span>▤</span>
                                    Positions
                                </div>

                                <div class="preview-sidebar-item">
                                    <span>◈</span>
                                    Evaluations
                                </div>

                                <div class="preview-sidebar-item">
                                    <span>◫</span>
                                    Grading
                                </div>

                                <div class="preview-sidebar-item">
                                    <span>▥</span>
                                    Recruitment
                                </div>

                                <div class="preview-sidebar-item">
                                    <span>▧</span>
                                    Reports
                                </div>
                            </div>

                            <div class="preview-main">

                                <div class="preview-topbar">
                                    <div>
                                        <small>Overview</small>
                                        <h3>Good morning, Administrator</h3>
                                    </div>

                                    <div class="preview-user">
                                        <span></span>
                                        Admin
                                    </div>
                                </div>

                                <div class="preview-stat-grid">

                                    <div class="preview-stat-card">
                                        <div class="preview-stat-icon blue">▦</div>
                                        <div>
                                            <small>Total positions</small>
                                            <strong>248</strong>
                                        </div>
                                    </div>

                                    <div class="preview-stat-card">
                                        <div class="preview-stat-icon amber">◈</div>
                                        <div>
                                            <small>Pending evaluations</small>
                                            <strong>18</strong>
                                        </div>
                                    </div>

                                    <div class="preview-stat-card">
                                        <div class="preview-stat-icon green">✓</div>
                                        <div>
                                            <small>Approved grades</small>
                                            <strong>126</strong>
                                        </div>
                                    </div>

                                </div>

                                <div class="preview-content-grid">

                                    <div class="preview-chart-card">
                                        <div class="preview-card-heading">
                                            <div>
                                                <small>Evaluation activity</small>
                                                <strong>Position evaluations</strong>
                                            </div>

                                            <span>2026</span>
                                        </div>

                                        <div class="chart-area">
                                            <div class="chart-line line-one"></div>
                                            <div class="chart-line line-two"></div>
                                            <div class="chart-line line-three"></div>
                                            <div class="chart-line line-four"></div>

                                            <div class="chart-bars">
                                                <span style="height: 38%"></span>
                                                <span style="height: 52%"></span>
                                                <span style="height: 45%"></span>
                                                <span style="height: 68%"></span>
                                                <span style="height: 58%"></span>
                                                <span style="height: 82%"></span>
                                                <span style="height: 74%"></span>
                                                <span style="height: 94%"></span>
                                            </div>
                                        </div>

                                        <div class="chart-labels">
                                            <span>Jan</span>
                                            <span>Feb</span>
                                            <span>Mar</span>
                                            <span>Apr</span>
                                            <span>May</span>
                                            <span>Jun</span>
                                            <span>Jul</span>
                                            <span>Aug</span>
                                        </div>
                                    </div>

                                    <div class="preview-grade-card">
                                        <div class="preview-card-heading">
                                            <div>
                                                <small>Grade distribution</small>
                                                <strong>Job grades</strong>
                                            </div>
                                        </div>

                                        <div class="grade-donut">
                                            <div class="donut-inner">
                                                <strong>82%</strong>
                                                <small>Evaluated</small>
                                            </div>
                                        </div>

                                        <div class="grade-legend">
                                            <span>
                                                <i class="legend-blue"></i>
                                                Grade 1–4
                                            </span>

                                            <span>
                                                <i class="legend-green"></i>
                                                Grade 5–7
                                            </span>

                                            <span>
                                                <i class="legend-amber"></i>
                                                Grade 8+
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="preview-table-card">
                                    <div class="preview-card-heading">
                                        <div>
                                            <small>Recent activity</small>
                                            <strong>Evaluation status</strong>
                                        </div>

                                        <span>View all →</span>
                                    </div>

                                    <div class="preview-table">
                                        <div class="preview-table-row">
                                            <span>Senior Accountant</span>
                                            <span>Finance</span>
                                            <b class="status-approved">Approved</b>
                                        </div>

                                        <div class="preview-table-row">
                                            <span>HR Officer</span>
                                            <span>Human Resources</span>
                                            <b class="status-pending">Pending</b>
                                        </div>

                                        <div class="preview-table-row">
                                            <span>IT Support Specialist</span>
                                            <span>ICT</span>
                                            <b class="status-review">Review</b>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="floating-card floating-card-one">
                        <span class="floating-icon green">✓</span>
                        <div>
                            <strong>Grade approved</strong>
                            <small>Senior Accountant · Grade 8</small>
                        </div>
                    </div>

                    <div class="floating-card floating-card-two">
                        <span class="floating-icon blue">◈</span>
                        <div>
                            <strong>Evaluation complete</strong>
                            <small>Total score: 82.50 / 100</small>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- Statistics -->
        <section class="stats-section">
            <div class="container stats-grid">

                <div class="stat-item">
                    <strong>01</strong>
                    <span>Centralized position management</span>
                </div>

                <div class="stat-item">
                    <strong>02</strong>
                    <span>Configurable evaluation methodologies</span>
                </div>

                <div class="stat-item">
                    <strong>03</strong>
                    <span>Transparent approval workflows</span>
                </div>

                <div class="stat-item">
                    <strong>04</strong>
                    <span>Reliable reports and audit history</span>
                </div>

            </div>
        </section>


        <!-- About -->
        <section class="content-section about-section" id="about">
            <div class="container section-grid">

                <div class="section-heading">
                    <span class="section-label">About PEJGMS</span>

                    <h2>
                        One platform for the
                        <span>entire position lifecycle.</span>
                    </h2>
                </div>

                <div class="section-description">
                    <p>
                        PEJGMS helps organizations manage positions from
                        initial creation and job analysis through evaluation,
                        grading, approval, recruitment, and periodic
                        re-evaluation.
                    </p>

                    <p>
                        The platform provides a consistent and auditable
                        approach to job evaluation while keeping scoring
                        factors, grade structures, salary scales, and approval
                        rules configurable.
                    </p>
                </div>

            </div>

            <div class="container feature-grid">

                <article class="feature-card">
                    <div class="feature-number">01</div>
                    <h3>Position management</h3>
                    <p>
                        Maintain accurate organizational structures,
                        departments, job families, job descriptions,
                        responsibilities, and requirements.
                    </p>
                    <a href="#workflow">Learn more →</a>
                </article>

                <article class="feature-card">
                    <div class="feature-number">02</div>
                    <h3>Data-driven evaluation</h3>
                    <p>
                        Evaluate positions using configurable factors,
                        criteria, weights, score ranges, and automatic grade
                        recommendations.
                    </p>
                    <a href="#methodology">Explore methodology →</a>
                </article>

                <article class="feature-card">
                    <div class="feature-number">03</div>
                    <h3>Secure governance</h3>
                    <p>
                        Support role-based access, committee review,
                        controlled approvals, audit logs, and protected
                        organizational data.
                    </p>
                    <a href="#workflow">View workflow →</a>
                </article>

            </div>
        </section>


        <!-- Methodology -->
        <section class="content-section methodology-section" id="methodology">
            <div class="container">

                <div class="centered-heading">
                    <span class="section-label">Evaluation methodology</span>

                    <h2>
                        Make every position
                        <span>measurable and fair.</span>
                    </h2>

                    <p>
                        Configure your own evaluation methodology instead of
                        relying on fixed factors or predefined grading rules.
                    </p>
                </div>

                <div class="methodology-grid">

                    <div class="methodology-card">
                        <div class="methodology-icon">01</div>
                        <h3>Define factors</h3>
                        <p>
                            Create factors such as education, experience,
                            complexity, responsibility, decision-making,
                            supervision, impact, and working conditions.
                        </p>
                    </div>

                    <div class="methodology-card">
                        <div class="methodology-icon">02</div>
                        <h3>Configure scoring</h3>
                        <p>
                            Set criteria, minimum and maximum points,
                            factor weights, evaluation guidance, and
                            evidence requirements.
                        </p>
                    </div>

                    <div class="methodology-card">
                        <div class="methodology-icon">03</div>
                        <h3>Map grades</h3>
                        <p>
                            Automatically recommend grades based on
                            configured score ranges and approved grading
                            structures.
                        </p>
                    </div>

                    <div class="methodology-card">
                        <div class="methodology-icon">04</div>
                        <h3>Review results</h3>
                        <p>
                            Compare evaluator scores, review differences,
                            record committee decisions, and maintain a
                            complete history.
                        </p>
                    </div>

                </div>

            </div>
        </section>


        <!-- Workflow -->
        <section class="content-section workflow-section" id="workflow">
            <div class="container">

                <div class="section-heading workflow-heading">
                    <span class="section-label">Complete workflow</span>

                    <h2>
                        From job creation
                        <span>to approved recruitment.</span>
                    </h2>

                    <p>
                        Follow a structured process that improves consistency,
                        accountability, and decision-making.
                    </p>
                </div>

                <div class="workflow-list">

                    <div class="workflow-step">
                        <span class="workflow-step-number">01</span>
                        <div>
                            <h3>Organization and position</h3>
                            <p>
                                Create the organizational structure and define
                                the position, job purpose, duties,
                                responsibilities, and qualifications.
                            </p>
                        </div>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-step-number">02</span>
                        <div>
                            <h3>Job analysis and evaluation</h3>
                            <p>
                                Assign evaluators and collect factor scores,
                                comments, evidence, and supporting
                                information.
                            </p>
                        </div>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-step-number">03</span>
                        <div>
                            <h3>Committee review and approval</h3>
                            <p>
                                Compare evaluator results, review the
                                recommendation, request revisions, or
                                approve the final grade.
                            </p>
                        </div>
                    </div>

                    <div class="workflow-step">
                        <span class="workflow-step-number">04</span>
                        <div>
                            <h3>Salary structure and recruitment</h3>
                            <p>
                                Connect approved grades to salary scales and
                                publish vacancies for recruitment when
                                authorized.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- Vacancies -->
        <section class="content-section vacancies-section" id="vacancies">
            <div class="container vacancy-banner">

                <div>
                    <span class="section-label">Public recruitment portal</span>

                    <h2>
                        Discover opportunities
                        <span>within your organization.</span>
                    </h2>

                    <p>
                        Approved positions can be published as vacancies,
                        allowing applicants to explore job details and
                        recruitment information through a public portal.
                    </p>
                </div>

                <div class="vacancy-actions">
                    <a href="{{ url('/vacancies') }}" class="btn btn-primary btn-large">
                        Browse vacancies
                        <span class="arrow">→</span>
                    </a>

                    <a href="{{ url('/positions') }}" class="text-button">
                        Explore positions
                        <span>↗</span>
                    </a>
                </div>

            </div>
        </section>


        <!-- Closing CTA -->
        <section class="closing-section">
            <div class="container closing-content">

                <span class="section-label">Ready to get started?</span>

                <h2>
                    Bring clarity to your
                    <span>organization's workforce.</span>
                </h2>

                <p>
                    Build a more transparent, consistent, and accountable
                    approach to position evaluation and job grading.
                </p>

                <div class="hero-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-large">
                            Go to dashboard
                            <span class="arrow">→</span>
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-large">
                                Get started
                                <span class="arrow">→</span>
                            </a>
                        @endif

                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-button">
                                Sign in to your account
                                <span>↗</span>
                            </a>
                        @endif
                    @endauth
                </div>

            </div>
        </section>

    </main>


    <!-- Footer -->
    <footer class="site-footer" id="contact">
        <div class="container footer-grid">

            <div class="footer-brand">

                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>

                    <span class="brand-copy">
                        <strong>PEJGMS</strong>
                        <small>Position Evaluation System</small>
                    </span>
                </a>

                <p>
                    A professional platform for systematic position
                    evaluation, job grading, salary management, and fair
                    recruitment.
                </p>
            </div>

            <div class="footer-column">
                <h4>Platform</h4>
                <a href="#about">About</a>
                <a href="#methodology">Methodology</a>
                <a href="#workflow">Workflow</a>
                <a href="#vacancies">Vacancies</a>
            </div>

            <div class="footer-column">
                <h4>Account</h4>

                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">Sign in</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>

            <div class="footer-column">
                <h4>Contact</h4>
                <a href="mailto:info@example.com">info@example.com</a>
                <span>Addis Ababa, Ethiopia</span>
                <span>Monday–Friday, 8:30–17:30</span>
            </div>

        </div>

        <div class="container footer-bottom">
            <p>
                © {{ date('Y') }} {{ config('app.name', 'PEJGMS') }}.
                All rights reserved.
            </p>

            <p>
                Position Evaluation & Job Grading Management System
            </p>
        </div>
    </footer>


    <script>
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const button = document.querySelector('.mobile-menu-button');

            const isOpen = mobileNav.classList.toggle('open');

            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        function closeMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const button = document.querySelector('.mobile-menu-button');

            mobileNav.classList.remove('open');
            button.setAttribute('aria-expanded', 'false');
        }
    </script>

</body>
</html>