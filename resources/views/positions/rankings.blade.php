@extends('layouts.app')

@section('title', 'Position Rankings')

@section('content')

<div class="page-header">

    <div>
        <h1>Position Rankings</h1>

        <p>
            Ranked list of evaluated positions based on their latest evaluation.
        </p>
    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>
            <h2>Ranked Positions</h2>

            <p>
                Higher scores indicate higher evaluation results.
            </p>
        </div>

    </div>


    @if($evaluations->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>Rank</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Score</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($evaluations as $evaluation)

                        <tr>

                            <td>

                                <strong>
                                    #{{ $loop->iteration }}
                                </strong>

                            </td>


                            <td>

                                <strong>
                                    {{ $evaluation->position->title ?? 'N/A' }}
                                </strong>

                            </td>


                            <td>

                                {{ $evaluation->position->department->name ?? 'N/A' }}

                            </td>


                            <td>

                                <strong>
                                    {{ number_format((float) $evaluation->total_score, 2) }}
                                </strong>

                                / 100

                            </td>


                            <td>

                                <span class="rating-badge">
                                    {{ $evaluation->rating }}
                                </span>

                            </td>


                            <td>

                                {{ ucfirst($evaluation->status) }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'positions.evaluation.show',
                                        [
                                            'position' => $evaluation->position_id,
                                            'evaluation' => $evaluation->id
                                        ]
                                    ) }}"
                                    class="evaluation-btn"
                                >
                                    View Result
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                📊
            </div>

            <h3>No Evaluated Positions</h3>

            <p>
                There are currently no completed position evaluations.
            </p>

        </div>

    @endif

</div>

@endsection