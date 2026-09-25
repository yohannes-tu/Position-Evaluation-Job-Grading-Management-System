@extends('layouts.app')

@section('title', 'Application ' . $application->application_number)

@section('content')

<div class="page-header">

    <div>

        <a
            href="{{ route('applications.index') }}"
            class="back-link"
        >
            ← Back to Applications
        </a>

        <h1>
            {{ $application->application_number }}
        </h1>

        <p>
            Application review
        </p>

    </div>


    <div>

        <span class="status-badge status-{{ $application->status }}">

            {{ ucwords(
                str_replace(
                    '_',
                    ' ',
                    $application->status
                )
            ) }}

        </span>

    </div>

</div>


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


<div class="details-grid">


    {{-- Applicant --}}

    <div class="card">

        <div class="card-header">

            <h2>
                Applicant Information
            </h2>

        </div>


        <div class="details-list">

            <div>
                <span>Name</span>

                <strong>
                    {{ $application->applicant->full_name }}
                </strong>
            </div>


            <div>
                <span>Email</span>

                <strong>
                    {{ $application->applicant->email }}
                </strong>
            </div>


            <div>
                <span>Phone</span>

                <strong>
                    {{ $application->applicant->phone }}
                </strong>
            </div>


            <div>
                <span>Gender</span>

                <strong>
                    {{ $application->applicant->gender
                        ? ucfirst($application->applicant->gender)
                        : '—' }}
                </strong>
            </div>


            <div>
                <span>Date of Birth</span>

                <strong>
                    {{ $application->applicant->date_of_birth?->format('d M Y') ?? '—' }}
                </strong>
            </div>


            <div>
                <span>National ID</span>

                <strong>
                    {{ $application->applicant->national_id ?? '—' }}
                </strong>
            </div>


            <div>
                <span>Region</span>

                <strong>
                    {{ $application->applicant->region ?? '—' }}
                </strong>
            </div>


            <div>
                <span>City</span>

                <strong>
                    {{ $application->applicant->city ?? '—' }}
                </strong>
            </div>


            <div>
                <span>Address</span>

                <strong>
                    {{ $application->applicant->address ?? '—' }}
                </strong>
            </div>

        </div>

    </div>


    {{-- Vacancy --}}

    <div class="card">

        <div class="card-header">

            <h2>
                Vacancy Information
            </h2>

        </div>


        <div class="details-list">

            <div>
                <span>Position</span>

                <strong>
                    {{ $application->vacancy->position->title }}
                </strong>
            </div>


            <div>
                <span>Vacancy Code</span>

                <strong>
                    {{ $application->vacancy->vacancy_code }}
                </strong>
            </div>


            <div>
                <span>Department</span>

                <strong>
                    {{ $application->vacancy->position->department->name }}
                </strong>
            </div>


            <div>
                <span>Grade</span>

                <strong>
                    {{ $application->vacancy->position->approvedGrade->name ?? '—' }}
                </strong>
            </div>


            <div>
                <span>Location</span>

                <strong>
                    {{ $application->vacancy->location ?? '—' }}
                </strong>
            </div>


            <div>
                <span>Application Date</span>

                <strong>
                    {{ $application->submitted_at?->format('d M Y, h:i A') }}
                </strong>
            </div>

        </div>

    </div>

</div>


{{-- Cover Letter --}}

<div class="card">

    <div class="card-header">

        <h2>
            Cover Letter
        </h2>

    </div>


    @if($application->cover_letter)

        <div class="text-content">

            {!! nl2br(e($application->cover_letter)) !!}

        </div>

    @else

        <p class="muted">
            No cover letter was provided.
        </p>

    @endif

</div>


{{-- Documents --}}

<div class="card">

    <div class="card-header">

        <h2>
            Submitted Documents
        </h2>

    </div>


    @if($application->documents->count())

        <div class="document-list">

            @foreach($application->documents as $document)

                <div class="document-item">

                    <div>

                        <strong>
                            {{ $document->document_type }}
                        </strong>

                        <small>
                            {{ $document->original_name }}
                        </small>

                    </div>


                    <a
                        href="{{ route(
                            'applications.documents.download',
                            [
                                'application' => $application,
                                'document' => $document,
                            ]
                        ) }}"
                        class="btn btn-sm btn-secondary"
                    >
                        Download
                    </a>

                </div>

            @endforeach

        </div>

    @else

        <p class="muted">
            No documents were submitted.
        </p>

    @endif

</div>


{{-- Recruitment Review --}}

<div class="card">

    <div class="card-header">

        <h2>
            Recruitment Review
        </h2>

    </div>


    <form
        method="POST"
        action="{{ route(
            'applications.status',
            $application
        ) }}"
    >

        @csrf

        @method('PATCH')


        <div class="form-grid">

            <div class="form-group">

                <label for="status">
                    Application Status *
                </label>

                <select
                    id="status"
                    name="status"
                    required
                >

                    <option
                        value="under_review"
                        @selected(
                            $application->status === 'under_review'
                        )
                    >
                        Under Review
                    </option>

                    <option
                        value="shortlisted"
                        @selected(
                            $application->status === 'shortlisted'
                        )
                    >
                        Shortlisted
                    </option>

                    <option
                        value="interview"
                        @selected(
                            $application->status === 'interview'
                        )
                    >
                        Interview
                    </option>

                    <option
                        value="selected"
                        @selected(
                            $application->status === 'selected'
                        )
                    >
                        Selected
                    </option>

                    <option
                        value="rejected"
                        @selected(
                            $application->status === 'rejected'
                        )
                    >
                        Rejected
                    </option>
                    

                </select>

            </div>


            <div class="form-group">

                <label for="screening_score">
                    Screening Score
                </label>

                <input
                    type="number"
                    id="screening_score"
                    name="screening_score"
                    min="0"
                    max="100"
                    step="0.01"
                    value="{{ old(
                        'screening_score',
                        $application->screening_score
                    ) }}"
                    placeholder="0 - 100"
                >

            </div>


            <div class="form-group">

                <label for="interview_at">
                    Interview Date & Time
                </label>

                <input
                    type="datetime-local"
                    id="interview_at"
                    name="interview_at"
                    value="{{ old(
                        'interview_at',
                        $application->interview_at
                            ? $application->interview_at->format('Y-m-d\TH:i')
                            : ''
                    ) }}"
                >

            </div>

        </div>


        <div class="form-group">

            <label for="recruiter_notes">
                Recruiter Notes
            </label>

            <textarea
                id="recruiter_notes"
                name="recruiter_notes"
                rows="6"
                placeholder="Enter internal recruitment notes..."
            >{{ old(
                'recruiter_notes',
                $application->recruiter_notes
            ) }}</textarea>

        </div>


        <div class="form-group">

            <label for="rejection_reason">
                Rejection Reason
            </label>

            <textarea
                id="rejection_reason"
                name="rejection_reason"
                rows="4"
                placeholder="Required when rejecting an application."
            >{{ old(
                'rejection_reason',
                $application->rejection_reason
            ) }}</textarea>

        </div>


        <div class="form-actions">

            @if(
                in_array(
                    $application->status,
                    ['shortlisted', 'interview']
                )
            )
                <a
                    href="{{ route('interviews.create', $application) }}"
                    class="btn btn-primary"
                >
                    Schedule Interview
                </a>
            @endif

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Recruitment Decision
            </button>

        </div>

    </form>

</div>


@if($application->status === 'selected')

    <div class="card">

        <div class="card-header">
            <h2>Finalize Hiring</h2>
        </div>

        <form
            method="POST"
            action="{{ route(
                'applications.hire',
                $application
            ) }}"
        >

            @csrf

            <div class="form-group">

                <label for="notes">
                    Hiring Notes
                </label>

                <textarea
                    name="notes"
                    id="notes"
                    rows="4"
                    placeholder="Enter hiring decision notes..."
                ></textarea>

            </div>

            <button
                type="submit"
                class="btn btn-primary"
                onclick="
                    return confirm(
                        'Confirm final hiring decision?'
                    )
                "
            >
                Confirm Hiring
            </button>

        </form>

    </div>

@endif


@if($application->hiringRecord)

    <div class="card">

        <div class="card-header">
            <h2>Hiring Information</h2>
        </div>

        <div class="detail-grid">

            <div>
                <span class="detail-label">
                    Hiring Status
                </span>

                <strong>
                    {{ ucfirst(
                        $application->hiringRecord->employment_status
                    ) }}
                </strong>
            </div>

            <div>
                <span class="detail-label">
                    Hired Date
                </span>

                <strong>
                    {{ $application->hiringRecord->hired_at
                        ? $application->hiringRecord->hired_at->format('d M Y')
                        : 'Not available'
                    }}
                </strong>
            </div>

            <div>
                <span class="detail-label">
                    Notes
                </span>

                <strong>
                    {{ $application->hiringRecord->notes ?: 'No notes' }}
                </strong>
            </div>

        </div>

        <form
            method="POST"
            action="{{ route(
                'applications.cancel-hiring',
                $application
            ) }}"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-danger"
                onclick="
                    return confirm(
                        'Cancel this hiring decision?'
                    )
                "
            >
                Cancel Hiring
            </button>

        </form>

    </div>

@endif


{{-- Recruitment Timeline --}}

<div class="card">

    <div class="card-header">

        <h2>
            Application Timeline
        </h2>

    </div>


    <div class="timeline">

        <div class="timeline-item">

            <strong>
                Application Submitted
            </strong>

            <span>
                {{ $application->submitted_at?->format('d M Y, h:i A') }}
            </span>

        </div>


        @if($application->shortlisted_at)

            <div class="timeline-item">

                <strong>
                    Shortlisted
                </strong>

                <span>
                    {{ $application->shortlisted_at->format('d M Y, h:i A') }}
                </span>

            </div>

        @endif


        @if($application->interview_at)

            <div class="timeline-item">

                <strong>
                    Interview
                </strong>

                <span>
                    {{ $application->interview_at->format('d M Y, h:i A') }}
                </span>

            </div>

        @endif


        @if($application->selected_at)

            <div class="timeline-item">

                <strong>
                    Selected
                </strong>

                <span>
                    {{ $application->selected_at->format('d M Y, h:i A') }}
                </span>

            </div>

        @endif


        @if($application->rejected_at)

            <div class="timeline-item">

                <strong>
                    Rejected
                </strong>

                <span>
                    {{ $application->rejected_at->format('d M Y, h:i A') }}
                </span>

            </div>

        @endif

    </div>

</div>

@endsection