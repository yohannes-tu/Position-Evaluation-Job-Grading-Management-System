@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<div class="page-header">
    <div>
        <h1>Reports</h1>
        <p>
            Generate and review position evaluation, grading,
            organizational, and recruitment reports.
        </p>
    </div>
</div>

<div class="reports-grid">

    {{-- Position Evaluation --}}
    <div class="report-card">
        <div class="report-icon">
            📊
        </div>

        <div class="report-content">
            <h2>Position Evaluation Report</h2>

            <p>
                View detailed evaluation results including factors,
                scores, weighted scores, total score, and recommended grade.
            </p>

            <a
    href="{{ route('reports.position-evaluation') }}"
    class="btn btn-primary"
>
    View Report
</a>
        </div>
    </div>


    {{-- Position Inventory --}}
    <div class="report-card">
        <div class="report-icon">
            📋
        </div>

        <div class="report-content">
            <h2>Position Inventory Report</h2>

            <p>
                View the complete organizational position inventory
                including departments, status, and evaluation information.
            </p>

            <<a
    href="{{ route('reports.position-inventory') }}"
    class="btn btn-primary"
>
    View Report
</a>
        </div>
    </div>


    {{-- Grade Distribution --}}
    <div class="report-card">
        <div class="report-icon">
            📈
        </div>

        <div class="report-content">
            <h2>Grade Distribution Report</h2>

            <p>
                Analyze how positions are distributed across
                organizational grades.
            </p>

           <a
    href="{{ route('reports.grade-distribution') }}"
    class="btn btn-primary"
>
    View Report
</a>
        </div>
    </div>


    {{-- Department Positions --}}
    <div class="report-card">
        <div class="report-icon">
            🏢
        </div>

        <div class="report-content">
            <h2>Department Position Report</h2>

            <p>
                Review positions grouped by department and
                organizational structure.
            </p>

            <a href="#" class="btn btn-primary">
                View Report
            </a>
        </div>
    </div>


    {{-- Evaluation Progress --}}
    <div class="report-card">
        <div class="report-icon">
            ⏳
        </div>

        <div class="report-content">
            <h2>Evaluation Progress Report</h2>

            <p>
                Monitor evaluated, pending, draft, and completed
                position evaluations.
            </p>

            <a
    href="{{ route('reports.evaluation-progress') }}"
    class="btn btn-primary"
>
    View Report
</a>
        </div>
    </div>


    {{-- Position Ranking --}}
    <div class="report-card">
        <div class="report-icon">
            🏆
        </div>

        <div class="report-content">
            <h2>Position Ranking Report</h2>

            <p>
                View positions ranked according to their
                evaluation scores.
            </p>

            <a
                href="{{ route('position-rankings.index') }}"
                class="btn btn-primary"
            >
                View Rankings
            </a>
        </div>
    </div>


    {{-- Salary / Grade --}}
    <div class="report-card">
        <div class="report-icon">
            💰
        </div>

        <div class="report-content">
            <h2>Salary & Grade Report</h2>

            <p>
                Review grade structures and configured salary
                information.
            </p>

            <a href="#" class="btn btn-primary">
                View Report
            </a>
        </div>
    </div>


    {{-- Audit --}}
    <div class="report-card">
        <div class="report-icon">
            🔐
        </div>

        <div class="report-content">
            <h2>Audit Report</h2>

            <p>
                Review important system activities,
                evaluation changes, and administrative actions.
            </p>

            <a href="#" class="btn btn-primary">
                View Report
            </a>
        </div>
    </div>

</div>

@endsection