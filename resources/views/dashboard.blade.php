@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <div class="dashboard-header">
        <div>
            <h2>Welcome, {{ auth()->user()->name }}</h2>
            <p>
                    You are logged in as
                    <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
                    Here's an overview of the Position Evaluation System.
            </p>
        </div>
    </div>

    <div class="dashboard-cards">

        <div class="dashboard-card">
            <div class="card-icon">
                ▤
            </div>

            <div>
                <span>Total Positions</span>
                <strong>{{ $statistics['positions'] }}</strong>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                ✓
            </div>

            <div>
                <span>Evaluated Positions</span>
                <strong>{{ $statistics['evaluated_positions'] }}</strong>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                ⏳
            </div>

            <div>
                <span>Pending Evaluations</span>
                <strong>{{ $statistics['pending_evaluations'] }}</strong>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                ▦
            </div>

            <div>
                <span>Departments</span>
                <strong>{{ $statistics['departments'] }}</strong>
            </div>
        </div>

    </div>

    <div class="dashboard-grid">

        @if(auth()->user()->role !== 'public_user')
        <div class="dashboard-panel">

            <div class="panel-header">
                <h3>Recent Evaluations</h3>
                <a href="{{ route('position-evaluation.statistics') }}">View All</a>
            </div>

            @if($recentEvaluations->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">▤</div>

                    <h4>No evaluations yet</h4>

                    <p>
                        Position evaluations will appear here once they are created.
                    </p>
                </div>
            @else
                <div class="recent-evaluations">
                    @foreach($recentEvaluations as $evaluation)
                        <a
                            href="{{ route('positions.evaluation.show', [$evaluation->position, $evaluation]) }}"
                            class="quick-action"
                        >
                            <span>✓</span>
                            <div>
                                <strong>{{ $evaluation->position->title }}</strong>
                                <small>
                                    Score: {{ number_format((float) $evaluation->total_score, 2) }}
                                </small>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>

        <div class="dashboard-panel">

            <div class="panel-header">
                <h3>Quick Actions</h3>
            </div>

            <div class="quick-actions">

                <a href="{{ route('positions.create') }}" class="quick-action">
                <span>+</span>

                <div>
                    <strong>Add Position</strong>
                    <small>Create a new job position</small>
                </div>
    </a>

    <a href="{{ route('positions.index') }}" class="quick-action">
        <span>✓</span>

        <div>
            <strong>New Evaluation</strong>
            <small>Start a position evaluation</small>
        </div>
    </a>

    <a href="{{ route('reports.index') }}" class="quick-action">
        <span>▥</span>

        <div>
            <strong>View Reports</strong>
            <small>View evaluation reports</small>
        </div>
    </a>

    <a href="{{ route('salary-scales.index') }}" class="quick-action">
        <span>$</span>

        <div>
            <strong>Salary Scales</strong>
            <small>Manage salary bands</small>
        </div>
    </a>

</div>

        </div>
        @endif

    </div>

    <div class="system-info-panel">

    <div class="panel-header">
        <h3>System Information</h3>
    </div>

    <div class="system-info-content">

        <div class="system-info-item">
            <span>System</span>
            <strong>Position Evaluation System</strong>
        </div>

        <div class="system-info-item">
            <span>Application</span>
            <strong>Laravel</strong>
        </div>

        <div class="system-info-item">
            <span>Database</span>
            <strong>MySQL</strong>
        </div>

        <div class="system-info-item">
            <span>Current User</span>
            <strong>{{ auth()->user()->name }}</strong>
        </div>

    </div>

</div>

@endsection