@extends('layouts.app')

@section('title', 'Vacancy Details')

@section('content')

<div class="page-header">
    <div>
        <h1>Vacancy Details</h1>
        <p>
            View vacancy information, status, and recruitment workflow.
        </p>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">

        <a
            href="{{ route('vacancies.index') }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

        <a
            href="{{ route(
                'applicant-rankings.index',
                $vacancy
            ) }}"
            class="btn btn-primary"
        >
            Applicant Ranking
        </a>

        @if(auth()->user()->role === 'public_user' && $vacancy->status === 'published')
            <a
                href="{{ route('public.applications.create', $vacancy) }}"
                class="btn btn-success"
            >
                Apply for this Position
            </a>
        @endif

        @if(!in_array($vacancy->status, ['published', 'closed', 'cancelled']))
            <a
                href="{{ route('vacancies.edit', $vacancy) }}"
                class="btn btn-primary"
            >
                Edit Vacancy
            </a>
        @endif

    </div>
</div>


{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


{{-- Vacancy Header --}}
<div class="card" style="margin-bottom:24px;">

    <div
        style="
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:20px;
            flex-wrap:wrap;
        "
    >

        <div>
            <div
                style="
                    color:#64748b;
                    font-size:14px;
                    margin-bottom:6px;
                "
            >
                {{ $vacancy->vacancy_code }}
            </div>

            <h2 style="margin:0 0 8px 0;">
                {{ $vacancy->position->title ?? 'Position' }}
            </h2>

            <p style="margin:0; color:#64748b;">
                {{ $vacancy->position->department->name ?? 'Department not specified' }}
            </p>
        </div>


        @php
            $statusClasses = [
                'draft' => 'status-draft',
                'pending_approval' => 'status-pending',
                'approved' => 'status-approved',
                'published' => 'status-published',
                'closed' => 'status-closed',
                'cancelled' => 'status-cancelled',
            ];

            $statusLabels = [
                'draft' => 'Draft',
                'pending_approval' => 'Pending Approval',
                'approved' => 'Approved',
                'published' => 'Published',
                'closed' => 'Closed',
                'cancelled' => 'Cancelled',
            ];
        @endphp

        <span
            class="status-badge {{ $statusClasses[$vacancy->status] ?? '' }}"
        >
            {{ $statusLabels[$vacancy->status] ?? ucfirst($vacancy->status) }}
        </span>

    </div>

</div>


{{-- Workflow Actions --}}
<div class="card" style="margin-bottom:24px;">

    <div class="card-header">
        <h3>Vacancy Workflow</h3>
    </div>

    <div style="padding:20px;">

        <div
            style="
                display:flex;
                align-items:center;
                gap:8px;
                flex-wrap:wrap;
                margin-bottom:24px;
            "
        >

            <span class="workflow-step
                {{ in_array($vacancy->status, [
                    'draft',
                    'pending_approval',
                    'approved',
                    'published',
                    'closed'
                ]) ? 'completed' : '' }}">
                Draft
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($vacancy->status, [
                    'pending_approval',
                    'approved',
                    'published',
                    'closed'
                ]) ? 'completed' : '' }}">
                Pending Approval
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($vacancy->status, [
                    'approved',
                    'published',
                    'closed'
                ]) ? 'completed' : '' }}">
                Approved
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ in_array($vacancy->status, [
                    'published',
                    'closed'
                ]) ? 'completed' : '' }}">
                Published
            </span>

            <span>→</span>

            <span class="workflow-step
                {{ $vacancy->status === 'closed' ? 'completed' : '' }}">
                Closed
            </span>

        </div>


        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            @if($vacancy->status === 'draft')

                <form
                    method="POST"
                    action="{{ route('vacancies.submit-approval', $vacancy) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Submit for Approval
                    </button>
                </form>

            @endif


            @if($vacancy->status === 'pending_approval')

                <form
                    method="POST"
                    action="{{ route('vacancies.approve', $vacancy) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Approve Vacancy
                    </button>
                </form>

            @endif


            @if($vacancy->status === 'approved')

                <form
                    method="POST"
                    action="{{ route('vacancies.publish', $vacancy) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Publish Vacancy
                    </button>
                </form>

            @endif


            @if($vacancy->status === 'published')

                <form
                    method="POST"
                    action="{{ route('vacancies.close', $vacancy) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-secondary"
                    >
                        Close Vacancy
                    </button>
                </form>

            @endif


            @if(in_array($vacancy->status, [
                'draft',
                'pending_approval',
                'approved'
            ]))
            

                <form
                    method="POST"
                    action="{{ route('vacancies.cancel', $vacancy) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Are you sure you want to cancel this vacancy?'
                        )"
                    >
                        Cancel Vacancy
                    </button>
                </form>

            @endif

        </div>

    </div>

</div>


{{-- Vacancy Information --}}
<div class="card" style="margin-bottom:24px;">

    <div class="card-header">
        <h3>Vacancy Information</h3>
    </div>

    <div
        style="
            display:grid;
            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));
            gap:20px;
            padding:20px;
        "
    >

        <div>
            <strong>Vacancy Code</strong>
            <p>{{ $vacancy->vacancy_code }}</p>
        </div>

        <div>
            <strong>Position</strong>
            <p>{{ $vacancy->position->title ?? '-' }}</p>
        </div>

        <div>
            <strong>Position Code</strong>
            <p>{{ $vacancy->position->code ?? '-' }}</p>
        </div>

        <div>
            <strong>Department</strong>
            <p>
                {{ $vacancy->position->department->name ?? '-' }}
            </p>
        </div>

        <div>
            <strong>Number of Openings</strong>
            <p>{{ $vacancy->number_of_openings }}</p>
        </div>

        <div>
            <strong>Employment Type</strong>
            <p>{{ $vacancy->employment_type }}</p>
        </div>

        <div>
            <strong>Location</strong>
            <p>{{ $vacancy->location }}</p>
        </div>

        <div>
            <strong>Posting Date</strong>
            <p>
                {{ \Carbon\Carbon::parse($vacancy->posting_date)->format('M d, Y') }}
            </p>
        </div>

        <div>
            <strong>Closing Date</strong>
            <p>
                {{ \Carbon\Carbon::parse($vacancy->closing_date)->format('M d, Y') }}
            </p>
        </div>

        <div>
            <strong>Salary Visibility</strong>

            <p>
                @if($vacancy->show_salary)
                    <span class="status-badge status-approved">
                        Visible
                    </span>
                @else
                    <span class="status-badge status-neutral">
                        Hidden
                    </span>
                @endif
            </p>

        </div>

    </div>

</div>


{{-- Description --}}
<div class="card" style="margin-bottom:24px;">

    <div class="card-header">
        <h3>Job Description</h3>
    </div>

    <div style="padding:20px; line-height:1.7;">
        {!! nl2br(e($vacancy->description)) !!}
    </div>

</div>


{{-- Application Instructions --}}
@if($vacancy->application_instructions)

    <div class="card" style="margin-bottom:24px;">

        <div class="card-header">
            <h3>Application Instructions</h3>
        </div>

        <div style="padding:20px; line-height:1.7;">
            {!! nl2br(e($vacancy->application_instructions)) !!}
        </div>

    </div>

@endif


{{-- Required Documents --}}
@if($vacancy->required_documents)

    <div class="card" style="margin-bottom:24px;">

        <div class="card-header">
            <h3>Required Documents</h3>
        </div>

        <div style="padding:20px; line-height:1.7;">
           @if(is_array($vacancy->required_documents))

    <ul style="margin:0; padding-left:20px;">
        @foreach($vacancy->required_documents as $document)
            <li style="margin-bottom:8px;">
                {{ $document }}
            </li>
        @endforeach
    </ul>

@elseif(!empty($vacancy->required_documents))

    {!! nl2br(e($vacancy->required_documents)) !!}

@else

    <span style="color:#64748b;">
        No specific documents listed.
    </span>

@endif
        </div>

    </div>

@endif


{{-- Position Information --}}
<div class="card">

    <div class="card-header">
        <h3>Related Position</h3>
    </div>

    <div style="padding:20px;">

        <p>
            <strong>Position:</strong>
            {{ $vacancy->position->title ?? '-' }}
        </p>

        <p>
            <strong>Code:</strong>
            {{ $vacancy->position->code ?? '-' }}
        </p>

        <p>
            <strong>Department:</strong>
            {{ $vacancy->position->department->name ?? '-' }}
        </p>

        @if($vacancy->position)
            <a
                href="{{ route('positions.show', $vacancy->position) }}"
                class="btn btn-secondary"
            >
                View Position
            </a>
        @endif

    </div>

</div>

@endsection