@extends('layouts.app')

@section('title', 'Recruitment Reports')

@section('content')

<div class="page-header">
    <div>
        <h1>Recruitment Reports</h1>
        <p>
            Review application progress, candidate scores and hiring decisions.
        </p>
    </div>

    <div>
        <a
            href="{{ route('recruitment.reports.export', request()->query()) }}"
            class="btn btn-primary"
        >
            Export CSV
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Report Filters</h2>
            <p>Filter recruitment records before viewing or exporting.</p>
        </div>
    </div>

    <form
        method="GET"
        action="{{ route('recruitment.reports') }}"
        class="form-grid"
    >
        <div class="form-group">
            <label for="vacancy_id">Vacancy</label>

            <select name="vacancy_id" id="vacancy_id">
                <option value="">All vacancies</option>

                @foreach($vacancies as $vacancy)
                    <option
                        value="{{ $vacancy->id }}"
                        @selected(request('vacancy_id') == $vacancy->id)
                    >
                        {{ $vacancy->vacancy_code }}
                        -
                        {{ $vacancy->position->title ?? 'Unknown position' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Application Status</label>

            <select name="status" id="status">
                <option value="">All statuses</option>

                @foreach($statuses as $status)
                    <option
                        value="{{ $status }}"
                        @selected(request('status') === $status)
                    >
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_from">Date From</label>

            <input
                type="date"
                name="date_from"
                id="date_from"
                value="{{ request('date_from') }}"
            >
        </div>

        <div class="form-group">
            <label for="date_to">Date To</label>

            <input
                type="date"
                name="date_to"
                id="date_to"
                value="{{ request('date_to') }}"
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Apply Filters
            </button>

            <a
                href="{{ route('recruitment.reports') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Application Report</h2>
            <p>
                Showing {{ $applications->total() }} application records.
            </p>
        </div>
    </div>

    @if($applications->count())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Application</th>
                        <th>Applicant</th>
                        <th>Position</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Application Score</th>
                        <th>Interview Score</th>
                        <th>Final Score</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($applications as $application)
                        @php
                            $latestInterview = $application->interviews
                                ->where('status', 'completed')
                                ->sortByDesc('created_at')
                                ->first();

                            $applicationScore = (float) (
                                $application->screening_score ?? 0
                            );

                            $interviewScore = (float) (
                                $latestInterview?->total_score ?? 0
                            );

                            $finalScore =
                                ($applicationScore * 0.40) +
                                ($interviewScore * 0.60);
                        @endphp

                        <tr>
                            <td>
                                <strong>
                                    {{ $application->application_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $application->applicant->full_name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $application->vacancy->position->title ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $application->submitted_at?->format('d M Y') }}
                            </td>

                            <td>
                                <span
                                    class="status-badge status-{{ $application->status }}"
                                >
                                    {{ ucwords(
                                        str_replace('_', ' ', $application->status)
                                    ) }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($applicationScore, 2) }}
                            </td>

                            <td>
                                {{ number_format($interviewScore, 2) }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($finalScore, 2) }}
                                </strong>
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

        <div class="pagination-wrapper">
            {{ $applications->links() }}
        </div>
    @else
        <div class="empty-state">
            <h3>No application records found</h3>
            <p>
                Try changing the filters or wait for applicants to submit applications.
            </p>
        </div>
    @endif
</div>

@endsection