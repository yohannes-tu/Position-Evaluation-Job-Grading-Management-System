@extends('layouts.app')

@section('title', 'Recruitment Dashboard')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Recruitment Dashboard
        </h1>

        <p>
            Monitor applications, candidates, interviews and recruitment progress.
        </p>

    </div>

    <div>

        <a
            href="{{ route('applications.index') }}"
            class="btn btn-primary"
        >
            View Applications
        </a>

    </div>

</div>


{{-- Statistics --}}

<div class="dashboard-grid">

    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Total Applications
        </span>

        <strong class="dashboard-card-value">
            {{ $totalApplications }}
        </strong>

        <span class="dashboard-card-meta">
            All submitted applications
        </span>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Active Vacancies
        </span>

        <strong class="dashboard-card-value">
            {{ $activeVacancies }}
        </strong>

        <span class="dashboard-card-meta">
            Currently published
        </span>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Under Review
        </span>

        <strong class="dashboard-card-value">
            {{ $underReview }}
        </strong>

        <span class="dashboard-card-meta">
            Awaiting screening
        </span>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Shortlisted
        </span>

        <strong class="dashboard-card-value">
            {{ $shortlisted }}
        </strong>

        <span class="dashboard-card-meta">
            Candidates shortlisted
        </span>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Interviews
        </span>

        <strong class="dashboard-card-value">
            {{ $interviews }}
        </strong>

        <span class="dashboard-card-meta">
            Interview stage
        </span>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Selected
        </span>

        <strong class="dashboard-card-value">
            {{ $selected }}
        </strong>

        <span class="dashboard-card-meta">
            Selected candidates
        </span>

    </div>

</div>


{{-- Recruitment Progress --}}

<div class="dashboard-two-column">


    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Recruitment Pipeline
                </h2>

                <p>
                    Current application distribution.
                </p>

            </div>

        </div>


        <div class="recruitment-pipeline">

            @foreach($applicationsByStatus as $status => $count)

                <div class="pipeline-row">

                    <div class="pipeline-label">

                        <span>
                            {{ $status }}
                        </span>

                        <strong>
                            {{ $count }}
                        </strong>

                    </div>


                    <div class="pipeline-bar">

                        <progress
                            class="pipeline-bar-fill"
                            value="{{ $count }}"
                            max="{{ max($totalApplications, 1) }}"
                            aria-label="{{ $status }} applications"
                        ></progress>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- Quick Actions --}}

    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Quick Actions
                </h2>

                <p>
                    Common recruitment tasks.
                </p>

            </div>

        </div>


        <div class="quick-actions">

            <a
                href="{{ route('applications.index', [
                    'status' => 'submitted'
                ]) }}"
                class="quick-action"
            >

                <strong>
                    New Applications
                </strong>

                <span>
                    {{ $submittedApplications }}
                </span>

            </a>


            <a
                href="{{ route('applications.index', [
                    'status' => 'shortlisted'
                ]) }}"
                class="quick-action"
            >

                <strong>
                    Shortlisted
                </strong>

                <span>
                    {{ $shortlisted }}
                </span>

            </a>


            <a
                href="{{ route('applications.index', [
                    'status' => 'interview'
                ]) }}"
                class="quick-action"
            >

                <strong>
                    Interviews
                </strong>

                <span>
                    {{ $interviews }}
                </span>

            </a>


            <a
                href="{{ route('applications.index', [
                    'status' => 'selected'
                ]) }}"
                class="quick-action"
            >

                <strong>
                    Selected
                </strong>

                <span>
                    {{ $selected }}
                </span>

            </a>

        </div>

    </div>

</div>


{{-- Recent Applications --}}

<div class="card">

    <div class="card-header">

        <div>

            <h2>
                Recent Applications
            </h2>

            <p>
                Latest candidates who submitted applications.
            </p>

        </div>


        <a
            href="{{ route('applications.index') }}"
            class="btn btn-secondary btn-sm"
        >
            View All
        </a>

    </div>


    @if($recentApplications->count())

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Application
                        </th>

                        <th>
                            Applicant
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($recentApplications as $application)

                        <tr>

                            <td>

                                <strong>
                                    {{ $application->application_number }}
                                </strong>

                            </td>


                            <td>

                                {{ $application->applicant->full_name }}

                                <small class="table-secondary">
                                    {{ $application->applicant->email }}
                                </small>

                            </td>


                            <td>

                                {{ $application->vacancy->position->title }}

                            </td>


                            <td>

                                {{ $application->submitted_at?->format('d M Y') }}

                            </td>


                            <td>

                                <span
                                    class="status-badge status-{{ $application->status }}"
                                >

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $application->status
                                        )
                                    ) }}

                                </span>

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'applications.show',
                                        $application
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    Review
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <h3>
                No applications yet
            </h3>

            <p>
                Applications submitted through published vacancies will appear here.
            </p>

        </div>

    @endif

</div>


{{-- Popular Vacancies --}}

<div class="card">

    <div class="card-header">

        <div>

            <h2>
                Vacancies by Application Volume
            </h2>

            <p>
                Vacancies receiving the most applications.
            </p>

        </div>

    </div>


    @if($topVacancies->count())

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Vacancy
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Applications
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($topVacancies as $vacancy)

                        <tr>

                            <td>
                                <strong>
                                    {{ $vacancy->vacancy_code }}
                                </strong>
                            </td>

                            <td>
                                {{ $vacancy->position->title }}
                            </td>

                            <td>
                                <strong>
                                    {{ $vacancy->applications_count }}
                                </strong>
                            </td>

                            <td>

                                <span class="status-badge status-published">
                                    Published
                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <h3>
                No application activity
            </h3>

            <p>
                Vacancy application statistics will appear here.
            </p>

        </div>

    @endif

</div>

@endsection