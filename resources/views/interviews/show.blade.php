@extends('layouts.app')

@section('title', 'Interview Details')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Interview Details
        </h1>

        <p>
            {{ $interview->application->applicant->full_name }}
        </p>

    </div>


    <a
        href="{{ route('interviews.index') }}"
        class="btn btn-secondary"
    >
        Back to Interviews
    </a>

</div>


<div class="dashboard-grid">


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Applicant
        </span>

        <strong class="dashboard-card-value interview-card-value">
            {{ $interview->application->applicant->full_name }}
        </strong>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Interview Date
        </span>

        <strong class="dashboard-card-value interview-card-value">
            {{ $interview->scheduled_at->format('d M Y') }}
        </strong>

    </div>


    <div class="dashboard-card">

        <span class="dashboard-card-label">
            Status
        </span>

        <strong class="dashboard-card-value interview-card-value">
            {{ ucwords(
                str_replace(
                    '_',
                    ' ',
                    $interview->status
                )
            ) }}
        </strong>

    </div>

</div>


<div class="dashboard-two-column">


    <div class="card">

        <div class="card-header">

            <h2>
                Interview Information
            </h2>

        </div>


        <div class="detail-grid">

            <div>

                <span class="detail-label">
                    Position
                </span>

                <strong>
                    {{ $interview->application->vacancy->position->title }}
                </strong>

            </div>


            <div>

                <span class="detail-label">
                    Type
                </span>

                <strong>
                    {{ ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $interview->interview_type
                        )
                    ) }}
                </strong>

            </div>


            <div>

                <span class="detail-label">
                    Date
                </span>

                <strong>
                    {{ $interview->scheduled_at->format('d M Y') }}
                </strong>

            </div>


            <div>

                <span class="detail-label">
                    Time
                </span>

                <strong>
                    {{ $interview->scheduled_at->format('h:i A') }}
                </strong>

            </div>


            <div>

                <span class="detail-label">
                    Duration
                </span>

                <strong>
                    {{ $interview->duration_minutes }} minutes
                </strong>

            </div>


            <div>

                <span class="detail-label">
                    Location
                </span>

                <strong>
                    {{ $interview->location ?: 'Not specified' }}
                </strong>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <h2>
                Interview Panel
            </h2>

        </div>


        @if($interview->panelMembers->count())

            <div class="panel-list">

                @foreach($interview->panelMembers as $member)

                    <div class="panel-list-item">

                        <strong>
                            {{ $member->user->name }}
                        </strong>

                        <span>
                            {{ $member->role ?: 'Panel Member' }}
                        </span>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">
                No panel members assigned.
            </div>

        @endif

    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>

            <h2>
                Interview Result
            </h2>

            <p>
                Interview scoring will be completed by the panel.
            </p>

        </div>

    </div>


    <div class="interview-result-summary">

        <div>

            <span>
                Total Score
            </span>

            <strong>
                {{ $interview->total_score !== null
                    ? number_format(
                        $interview->total_score,
                        2
                    )
                    : 'Not scored'
                }}
            </strong>

        </div>


        <div>

            <span>
                Recommendation
            </span>

            <strong>

                {{ ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $interview->recommendation
                    )
                ) }}

            </strong>
            @if($interview->status === 'completed')

    <div class="form-actions" style="margin-top: 20px;">

        <a
            href="{{ route('interviews.scoring', $interview) }}"
            class="btn btn-primary"
        >
            Score Interview
        </a>

    </div>

@endif

        </div>

    </div>

</div>


@if(
    $interview->status === 'completed' &&
    $interview->total_score !== null
)

    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Interview Recommendation
                </h2>

                <p>
                    Record the panel's overall recommendation.
                </p>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route(
                'interviews.recommendation',
                $interview
            ) }}"
        >

            @csrf

            <div class="form-group">

                <label for="recommendation">
                    Recommendation
                </label>

                <select
                    id="recommendation"
                    name="recommendation"
                    required
                >

                    <option value="">
                        Select recommendation
                    </option>

                    <option
                        value="recommended"
                        @selected(
                            $interview->recommendation === 'recommended'
                        )
                    >
                        Recommended
                    </option>

                    <option
                        value="reserve"
                        @selected(
                            $interview->recommendation === 'reserve'
                        )
                    >
                        Reserve
                    </option>

                    <option
                        value="not_recommended"
                        @selected(
                            $interview->recommendation === 'not_recommended'
                        )
                    >
                        Not Recommended
                    </option>

                </select>

            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Recommendation
                </button>

            </div>

        </form>

    </div>

@endif


@if($interview->status !== 'completed')

    <div class="card">

        <div class="card-header">

            <h2>
                Interview Actions
            </h2>

        </div>


        <div class="form-actions">

            <form
                method="POST"
                action="{{ route(
                    'interviews.complete',
                    $interview
                ) }}"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Mark as Completed
                </button>

            </form>

            @if($interview->status === 'completed')

    <a
        href="{{ route(
            'interviews.scoring',
            $interview
        ) }}"
        class="btn btn-primary"
    >
        Score Interview
    </a>

@endif


            @if(
                !in_array(
                    $interview->status,
                    ['cancelled']
                )
            )

                <form
                    method="POST"
                    action="{{ route(
                        'interviews.cancel',
                        $interview
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to cancel this interview?'
                    );"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="reason"
                        value="Interview cancelled by recruitment administrator."
                    >

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Cancel Interview
                    </button>

                </form>

            @endif

        </div>

    </div>

@endif

@endsection