@extends('layouts.app')

@section('title', 'Interview Scoring')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Interview Scoring
        </h1>

        <p>
            Evaluate the applicant using the configured interview criteria.
        </p>

    </div>

    <a
        href="{{ route('interviews.show', $interview) }}"
        class="btn btn-secondary"
    >
        Back to Interview
    </a>

</div>


<div class="card">

    <div class="card-header">

        <h2>
            Applicant
        </h2>

    </div>

    <div class="detail-grid">

        <div>

            <span class="detail-label">
                Applicant
            </span>

            <strong>
                {{ $interview->application->applicant->full_name }}
            </strong>

        </div>

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
                Interview Date
            </span>

            <strong>
                {{ $interview->scheduled_at->format('d M Y h:i A') }}
            </strong>

        </div>

        <div>

            <span class="detail-label">
                Panel Members
            </span>

            <strong>
                {{ $interview->panelMembers->count() }}
            </strong>

        </div>

    </div>

</div>


@if($criteria->sum('weight') != 100)

    <div class="alert alert-warning">

        <strong>
            Warning:
        </strong>

        Interview criteria weights currently total
        {{ number_format($criteria->sum('weight'), 2) }}%.

        They should total 100% before final scoring.

    </div>

@endif


<form
    method="POST"
    action="{{ route(
        'interviews.scores.save',
        $interview
    ) }}"
>

    @csrf


    @foreach($interview->panelMembers as $member)

        <div class="card">

            <div class="card-header">

                <div>

                    <h2>
                        {{ $member->user->name }}
                    </h2>

                    <p>
                        {{ $member->role ?: 'Panel Member' }}
                    </p>

                </div>

            </div>


            <div class="table-responsive">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Criterion
                            </th>

                            <th>
                                Weight
                            </th>

                            <th>
                                Maximum
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Comments
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($criteria as $criterion)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $criterion->name }}
                                    </strong>

                                    @if($criterion->description)

                                        <small class="table-secondary">
                                            {{ $criterion->description }}
                                        </small>

                                    @endif

                                </td>


                                <td>
                                    {{ number_format(
                                        $criterion->weight,
                                        2
                                    ) }}%
                                </td>


                                <td>
                                    {{ number_format(
                                        $criterion->max_score,
                                        2
                                    ) }}
                                </td>


                                <td>

                                    <input
                                        type="number"
                                        name="scores[{{ $member->id }}][{{ $criterion->id }}]"
                                        min="0"
                                        max="{{ $criterion->max_score }}"
                                        step="0.01"
                                        value="{{
                                            old(
                                                "scores.{$member->id}.{$criterion->id}"
                                            )
                                        }}"
                                        required
                                    >

                                </td>


                                <td>

                                    <textarea
                                        name="comments[{{ $member->id }}][{{ $criterion->id }}]"
                                        rows="2"
                                        placeholder="Optional comments..."
                                    >{{ old(
                                        "comments.{$member->id}.{$criterion->id}"
                                    ) }}</textarea>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endforeach


    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Scoring Rules
                </h2>

                <p>
                    Each criterion is normalized against its maximum score,
                    multiplied by its configured weight, and then averaged
                    across the interview panel.
                </p>

            </div>

        </div>


        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Panel Scores
            </button>

        </div>

    </div>

</form>

@endsection