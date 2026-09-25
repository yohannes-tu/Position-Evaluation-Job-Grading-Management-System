@extends('layouts.app')

@section('title', 'Applications')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Applications
        </h1>

        <p>
            Review and manage vacancy applications.
        </p>

    </div>

</div>


{{-- Flash Messages --}}

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


{{-- Filters --}}

<div class="card">

    <form
        method="GET"
        action="{{ route('applications.index') }}"
    >

        <div class="form-grid">

            <div class="form-group">

                <label for="search">
                    Search
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Applicant name, email or application number"
                >

            </div>


            <div class="form-group">

                <label for="vacancy_id">
                    Vacancy
                </label>

                <select
                    id="vacancy_id"
                    name="vacancy_id"
                >

                    <option value="">
                        All Vacancies
                    </option>

                    @foreach($vacancies as $vacancy)

                        <option
                            value="{{ $vacancy->id }}"
                            @selected(
                                request('vacancy_id') == $vacancy->id
                            )
                        >

                            {{ $vacancy->vacancy_code }}
                            -
                            {{ $vacancy->position->title }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="submitted"
                        @selected(request('status') === 'submitted')
                    >
                        Submitted
                    </option>

                    <option
                        value="under_review"
                        @selected(request('status') === 'under_review')
                    >
                        Under Review
                    </option>

                    <option
                        value="shortlisted"
                        @selected(request('status') === 'shortlisted')
                    >
                        Shortlisted
                    </option>

                    <option
                        value="interview"
                        @selected(request('status') === 'interview')
                    >
                        Interview
                    </option>

                    <option
                        value="selected"
                        @selected(request('status') === 'selected')
                    >
                        Selected
                    </option>

                    <option
                        value="rejected"
                        @selected(request('status') === 'rejected')
                    >
                        Rejected
                    </option>

                </select>

            </div>

        </div>


        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('applications.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Applications Table --}}

<div class="card">

    <div class="card-header">

        <div>

            <h2>
                Application List
            </h2>

            <p>
                {{ $applications->total() }}
                application(s)
            </p>

        </div>

    </div>


    @if($applications->count())

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Application #
                        </th>

                        <th>
                            Applicant
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Applied
                        </th>

                        <th>
                            Score
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

                    @foreach($applications as $application)

                        <tr>

                            <td>

                                <strong>
                                    {{ $application->application_number }}
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

                                {{ $application->vacancy->position->title }}

                                <small class="table-secondary">
                                    {{ $application->vacancy->vacancy_code }}
                                </small>

                            </td>


                            <td>

                                {{ $application->submitted_at?->format('d M Y') }}

                            </td>


                            <td>

                                @if($application->screening_score !== null)

                                    {{ number_format(
                                        $application->screening_score,
                                        2
                                    ) }}

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                <span class="status-badge status-{{ $application->status }}">

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


        <div class="pagination-wrapper">

            {{ $applications->links() }}

        </div>

    @else

        <div class="empty-state">

            <h3>
                No applications found
            </h3>

            <p>
                No applications match the current filters.
            </p>

        </div>

    @endif

</div>

@endsection