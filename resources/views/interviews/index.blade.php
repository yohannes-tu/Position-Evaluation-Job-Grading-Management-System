@extends('layouts.app')

@section('title', 'Interviews')

@section('content')

<div class="page-header">

    <div>
        <h1>Interviews</h1>

        <p>
            Schedule and manage applicant interviews.
        </p>
    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>
            <h2>Interview Schedule</h2>

            <p>
                View upcoming and completed interviews.
            </p>
        </div>

    </div>


    <form
        method="GET"
        action="{{ route('interviews.index') }}"
        class="filter-form"
    >

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search applicant..."
        >

        <select name="status">

            <option value="">
                All statuses
            </option>

            <option
                value="scheduled"
                @selected(request('status') === 'scheduled')
            >
                Scheduled
            </option>

            <option
                value="confirmed"
                @selected(request('status') === 'confirmed')
            >
                Confirmed
            </option>

            <option
                value="completed"
                @selected(request('status') === 'completed')
            >
                Completed
            </option>

            <option
                value="cancelled"
                @selected(request('status') === 'cancelled')
            >
                Cancelled
            </option>

        </select>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Search
        </button>

        <a
            href="{{ route('interviews.index') }}"
            class="btn btn-secondary"
        >
            Reset
        </a>

    </form>


    @if($interviews->count())

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            Applicant
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Date & Time
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Panel
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

                    @foreach($interviews as $interview)

                        <tr>

                            <td>

                                <strong>
                                    {{ $interview->application->applicant->full_name }}
                                </strong>

                                <small class="table-secondary">
                                    {{ $interview->application->applicant->email }}
                                </small>

                            </td>

                            <td>
                                {{ $interview->application->vacancy->position->title }}
                            </td>

                            <td>

                                {{ $interview->scheduled_at->format('d M Y') }}

                                <small class="table-secondary">
                                    {{ $interview->scheduled_at->format('h:i A') }}
                                </small>

                            </td>

                            <td>
                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $interview->interview_type
                                    )
                                ) }}
                            </td>

                            <td>
                                {{ $interview->panelMembers->count() }}
                                member(s)
                            </td>

                            <td>

                                <span
                                    class="status-badge status-{{ $interview->status }}"
                                >
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $interview->status
                                        )
                                    ) }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'interviews.show',
                                        $interview
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <div class="pagination-wrapper">
            {{ $interviews->links() }}
        </div>

    @else

        <div class="empty-state">

            <h3>
                No interviews found
            </h3>

            <p>
                Scheduled applicant interviews will appear here.
            </p>

        </div>

    @endif

</div>

@endsection