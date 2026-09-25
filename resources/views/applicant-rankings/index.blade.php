@extends('layouts.app')

@section('title', 'Applicant Ranking')

@section('content')

<div class="page-header">

    <div>
        <h1>Applicant Ranking</h1>

        <p>
            Compare applicants for
            <strong>
                {{ $vacancy->position->title }}
            </strong>
        </p>
    </div>
    <div class="card">

    <form
        method="GET"
        action="{{ route('applicant-rankings.index', $vacancy) }}"
    >

        <div class="form-grid">

            <div class="form-group">

                <label for="status">
                    Application Status
                </label>

                <select
                    name="status"
                    id="status"
                >

                    <option value="">
                        All active applicants
                    </option>

                    @foreach([
                        'submitted',
                        'under_review',
                        'shortlisted',
                        'interview',
                        'selected',
                        'rejected'
                    ] as $status)

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

                <label for="minimum_score">
                    Minimum Final Score
                </label>

                <input
                    type="number"
                    name="minimum_score"
                    id="minimum_score"
                    min="0"
                    max="100"
                    step="0.01"
                    value="{{ request('minimum_score') }}"
                    placeholder="Example: 70"
                >

            </div>

        </div>


        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Filter Ranking
            </button>

            <a
                href="{{ route('applicant-rankings.index', $vacancy) }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

</div>

    <a
        href="{{ route('vacancies.show', $vacancy) }}"
        class="btn btn-secondary"
    >
        Back to Vacancy
    </a>

</div>


<div class="card">

    <div class="card-header">

        <div>
            <h2>
                {{ $vacancy->vacancy_code }}
            </h2>

            <p>
                {{ $vacancy->position->title }}
            </p>
        </div>

        <div>
            <strong>
                {{ $applications->count() }}
            </strong>

            applicants
        </div>

    </div>

    <div class="table-responsive">

        <table class="data-table">

            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Applicant</th>
                    <th>Application Score</th>
                    <th>Interview Score</th>
                    <th>Final Score</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($applications as $application)

                    <tr
                        class="
                            @if($application->ranking_position === 1)
                                ranking-first
                            @elseif($application->ranking_position === 2)
                                ranking-second
                            @elseif($application->ranking_position === 3)
                                ranking-third
                            @endif
                        "
                    >

                        <td>

                            @if($application->ranking_position === 1)

                                <span class="ranking-medal">
                                    🥇
                                </span>

                            @elseif($application->ranking_position === 2)

                                <span class="ranking-medal">
                                    🥈
                                </span>

                            @elseif($application->ranking_position === 3)

                                <span class="ranking-medal">
                                    🥉
                                </span>

                            @endif

                            <strong>
                                #{{ $application->ranking_position }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ $application->applicant->full_name }}
                            </strong>

                            <small class="table-secondary">
                                {{ $application->applicant->email }}
                            </small>
                        </td>

                        <td>
                            {{ number_format(
                                $application->ranking_application_score,
                                2
                            ) }}
                        </td>

                        <td>
                            @if($application->ranking_interview)
                                {{ number_format(
                                    $application->ranking_interview_score,
                                    2
                                ) }}
                            @else
                                <span class="status-badge status-pending">
                                    Not scored
                                </span>
                            @endif
                        </td>

                        <td>
                            <strong>
                                {{ number_format(
                                    $application->ranking_final_score,
                                    2
                                ) }}
                            </strong>
                        </td>

                        <td>
                            <span class="status-badge">
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
        class="btn btn-secondary btn-sm"
    >
        Review
    </a>


    @if(
        in_array(
            $application->status,
            [
                'shortlisted',
                'interview',
                'under_review'
            ]
        )
    )

        <form
            method="POST"
            action="{{ route(
                'applications.select',
                $application
            ) }}"
            style="display: inline;"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-primary btn-sm"
                onclick="
                    return confirm(
                        'Select this applicant for the position?'
                    )
                "
            >
                Select
            </button>

        </form>
        @if($application->status === 'selected')

    <form
        method="POST"
        action="{{ route(
            'applications.hire',
            $application
        ) }}"
        style="display: inline;"
    >

        @csrf

        <button
            type="submit"
            class="btn btn-primary btn-sm"
            onclick="
                return confirm(
                    'Hire this applicant? This will create a hiring record.'
                )
            "
        >
            Hire
        </button>

    </form>

@endif

@if($application->status === 'hired')

    <span class="status-badge status-approved">
        Hired
    </span>

@endif

        <form
            method="POST"
            action="{{ route(
                'applications.reject',
                $application
            ) }}"
            style="display: inline;"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-danger btn-sm"
                onclick="
                    return confirm(
                        'Reject this applicant?'
                    )
                "
            >
                Reject
            </button>

        </form>

    @endif

</td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">
                            No applicants are available for ranking.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>
            <h2>Scoring Method</h2>

            <p>
                Final scores are calculated from application screening
                and completed interview scores.
            </p>
        </div>

    </div>

    <div class="detail-grid">

        <div>
            <span class="detail-label">
                Application screening
            </span>

            <strong>
                40%
            </strong>
        </div>

        <div>
            <span class="detail-label">
                Interview evaluation
            </span>

            <strong>
                60%
            </strong>
        </div>

        <div>
            <span class="detail-label">
                Total
            </span>

            <strong>
                100%
            </strong>
        </div>

    </div>

</div>

@endsection