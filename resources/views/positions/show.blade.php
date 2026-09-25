@extends('layouts.app')

@section('content')

<div class="page-container">

    {{-- Page Header --}}
    <div class="page-header">

        <div>

            <h1>{{ $position->title }}</h1>

            <p>
                Position details and requirements.
            </p>

        </div>

        <div class="header-actions">

            @if(auth()->user()->role === 'admin')

                <a
                    href="{{ route('positions.edit', $position) }}"
                    class="btn-primary">
                    Edit Position
                </a>

            @endif

        </div>

    </div>


    {{-- Position Summary --}}
    <div class="card position-summary">

        <div class="position-summary-header">

            <div>

                <span class="position-code-large">
                    {{ $position->code }}
                </span>

                <h2>
                    {{ $position->title }}
                </h2>

                @if($position->department)

                    <p class="department-name">
                        {{ $position->department->name }}
                    </p>

                @endif

            </div>


            <div>

                @if($position->is_active)

                    <span class="status-badge status-active">
                        Active
                    </span>

                @else

                    <span class="status-badge status-inactive">
                        Inactive
                    </span>

                @endif

            </div>

        </div>


        {{-- Basic Information --}}
        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Position Code
                </span>

                <span class="detail-value">
                    {{ $position->code }}
                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Department
                </span>

                <span class="detail-value">

                    @if($position->department)

                        {{ $position->department->name }}

                    @else

                        Not assigned

                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Employment Type
                </span>

                <span class="detail-value">

                    @switch($position->employment_type)

                        @case('full_time')
                            Full Time
                            @break

                        @case('part_time')
                            Part Time
                            @break

                        @case('contract')
                            Contract
                            @break

                        @default
                            Not specified

                    @endswitch

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Minimum Education
                </span>

                <span class="detail-value">

                    {{ $position->education ?: 'Not specified' }}

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Minimum Experience
                </span>

                <span class="detail-value">

                    {{ $position->experience ?: 'Not specified' }}

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span class="detail-value">

                    {{ $position->is_active ? 'Active' : 'Inactive' }}

                </span>

            </div>

        </div>

    </div>


    {{-- Description --}}
    <div class="card">

        <div class="card-header">

            <h2>Position Description</h2>

        </div>

        <div class="content-section">

            @if($position->description)

                <p class="description-text">
                    {{ $position->description }}
                </p>

            @else

                <p class="text-muted">
                    No description has been provided.
                </p>

            @endif

        </div>

    </div>


    {{-- Responsibilities --}}
    <div class="card">

        <div class="card-header">

            <h2>Main Responsibilities</h2>

        </div>

        <div class="content-section">

            @if($position->responsibilities)

                <div class="responsibilities-text">

                    {!! nl2br(e($position->responsibilities)) !!}

                </div>

            @else

                <p class="text-muted">
                    No responsibilities have been provided.
                </p>

            @endif

        </div>

    </div>


    {{-- Future Evaluation Section --}}
    <div class="card evaluation-placeholder">

        <div class="card-header">

            <h2>Position Evaluation</h2>

        </div>

        <div class="content-section">

            <p>
                This position has not been evaluated yet.
            </p>

            <p class="text-muted">
                Position evaluation and grade determination will be
                available in a later phase.
            </p>

            @if(auth()->user()->role === 'admin')

                <button
                    type="button"
                    class="btn-primary"
                    disabled>
                    Evaluate Position
                </button>

            @endif

        </div>

    </div>
    <div>
    <strong>Status</strong>

    @php
        $positionStatusLabels = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_evaluation' => 'Under Evaluation',
            'evaluated' => 'Evaluated',
            'committee_review' => 'Committee Review',
            'hr_review' => 'HR Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'suspended' => 'Suspended',
            'archived' => 'Archived',
        ];
    @endphp

    <p>
        <span class="status-badge
            status-{{ $position->status }}">
            {{ $positionStatusLabels[$position->status] ?? ucfirst($position->status) }}
        </span>
    </p>
</div>
@if($position->approvedGrade)

    <div>
        <strong>Approved Grade</strong>

        <p>
            {{ $position->approvedGrade->name }}
        </p>
    </div>

@endif

@if($position->approved_at)

    <div>
        <strong>Approved At</strong>

        <p>
            {{ $position->approved_at->format('M d, Y H:i') }}
        </p>
    </div>

@endif




    {{-- Bottom Actions --}}
   <div class="page-actions">

    <div class="action-left">

        <a
            href="{{ route('positions.index') }}"
            class="btn-secondary">
            ← Back to Positions
        </a>

    </div>


    <div class="action-right">

        @if(auth()->user()->role === 'admin')

            <a
                href="{{ route('positions.edit', $position) }}"
                class="btn-primary">
                Edit Position
            </a>
            <a
    href="{{ route('positions.evaluate', ['position' => $position->id]) }}"
    class="btn btn-primary"
>
    Evaluate Position
</a>

<a
    href="{{ route('positions.evaluations.index', ['position' => $position->id]) }}"
    class="btn btn-secondary"
>
    Evaluation History
</a>


            @if($position->is_active)

                <form
                    method="POST"
                    action="{{ route('positions.deactivate', $position) }}"
                    style="display: inline;"
                    onsubmit="return confirm('Are you sure you want to deactivate this position?');">

                    @csrf

                    @method('PATCH')

                    <button
                        type="submit"
                        class="btn-danger">
                        Deactivate
                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('positions.activate', $position) }}"
                    style="display: inline;"
                    onsubmit="return confirm('Are you sure you want to activate this position?');">

                    @csrf

                    @method('PATCH')

                    <button
                        type="submit"
                        class="btn-success">
                        Activate
                    </button>

                </form>

            @endif

        @endif

    </div>

</div>

</div>

<div class="card" style="margin-top:24px;">

    <div class="card-header">
        <h3>Position Approval Workflow</h3>
    </div>

    <div style="padding:20px;">

        <div style="
            display:flex;
            gap:8px;
            flex-wrap:wrap;
            align-items:center;
            margin-bottom:20px;
        ">

            <span class="workflow-step
                {{ in_array($position->status, [
                    'draft',
                    'submitted',
                    'under_evaluation',
                    'evaluated',
                    'committee_review',
                    'hr_review',
                    'approved'
                ]) ? 'completed' : '' }}">
                Draft
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($position->status, [
                    'submitted',
                    'under_evaluation',
                    'evaluated',
                    'committee_review',
                    'hr_review',
                    'approved'
                ]) ? 'completed' : '' }}">
                Submitted
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($position->status, [
                    'under_evaluation',
                    'evaluated',
                    'committee_review',
                    'hr_review',
                    'approved'
                ]) ? 'completed' : '' }}">
                Evaluation
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($position->status, [
                    'committee_review',
                    'hr_review',
                    'approved'
                ]) ? 'completed' : '' }}">
                Committee
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($position->status, [
                    'hr_review',
                    'approved'
                ]) ? 'completed' : '' }}">
                HR Review
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ $position->status === 'approved' ? 'completed' : '' }}">
                Approved
            </span>

        </div>


        <div style="
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        ">

            @if($position->status === 'draft')

                <form
                    method="POST"
                    action="{{ route('positions.submit', $position) }}"
                >
                    @csrf

                    <button class="btn btn-primary">
                        Submit Position
                    </button>
                </form>

            @endif


            @if($position->status === 'submitted')

                <form
                    method="POST"
                    action="{{ route(
                        'positions.start-evaluation',
                        $position
                    ) }}"
                >
                    @csrf

                    <button class="btn btn-primary">
                        Start Evaluation
                    </button>
                </form>

            @endif


            @if($position->status === 'under_evaluation')

                <a
                    href="{{ route(
                        'positions.evaluate',
                        $position
                    ) }}"
                    class="btn btn-primary"
                >
                    Evaluate Position
                </a>

            @endif


            @if($position->status === 'evaluated')

                <form
                    method="POST"
                    action="{{ route(
                        'positions.committee-review',
                        $position
                    ) }}"
                >
                    @csrf

                    <button class="btn btn-primary">
                        Send to Committee
                    </button>
                </form>

            @endif


            @if($position->status === 'committee_review')

                <form
                    method="POST"
                    action="{{ route(
                        'positions.hr-review',
                        $position
                    ) }}"
                >
                    @csrf

                    <button class="btn btn-primary">
                        Send to HR Review
                    </button>
                </form>

            @endif


            @if($position->status === 'hr_review')

                <form
                    method="POST"
                    action="{{ route(
                        'positions.approve',
                        $position
                    ) }}"
                >
                    @csrf

                    <button
                        class="btn btn-success"
                        onclick="
                            return confirm(
                                'Approve this position?'
                            )
                        "
                    >
                        Approve Position
                    </button>
                </form>

            @endif

        </div>

    </div>

</div>

@endsection