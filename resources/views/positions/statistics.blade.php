@extends('layouts.app')

@section('title', 'Evaluation Statistics')

@section('content')

<div class="page-header">

    <div>
        <h1>Evaluation Statistics</h1>

        <p>
            Overview of completed position evaluations.
        </p>
    </div>

    <a
        href="{{ route('position-rankings.index') }}"
        class="btn btn-primary"
    >
        View Rankings
    </a>

</div>


<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-label">
            Evaluated Positions
        </div>

        <div class="stat-value">
            {{ $totalEvaluated }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Average Score
        </div>

        <div class="stat-value">
            {{ number_format((float) $averageScore, 2) }}
        </div>

        <div class="stat-subtitle">
            out of 100
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Highest Score
        </div>

        <div class="stat-value">
            {{ number_format((float) $highestScore, 2) }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Lowest Score
        </div>

        <div class="stat-value">
            {{ number_format((float) $lowestScore, 2) }}
        </div>

    </div>

</div>


<div class="card" style="margin-top: 24px;">

    <div class="card-header">

        <div>
            <h2>Rating Distribution</h2>

            <p>
                Number of positions in each rating category.
            </p>
        </div>

    </div>


    <div style="padding: 20px;">

        @foreach($ratingCounts as $rating => $count)

            <div style="margin-bottom: 20px;">

                <div style="
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 7px;
                ">

                    <strong>
                        {{ $rating }}
                    </strong>

                    <span>
                        {{ $count }}
                    </span>

                </div>


                @php
                    $percentage = $totalEvaluated > 0
                        ? ($count / $totalEvaluated) * 100
                        : 0;
                @endphp


                <div style="
                    width: 100%;
                    height: 10px;
                    background: #e2e8f0;
                    border-radius: 10px;
                    overflow: hidden;
                ">

                    <div class="h-full rounded-[10px] bg-blue-600 w-[{{ $percentageValue }}%]"></div>

                </div>

            </div>

        @endforeach

    </div>

</div>


<div class="card" style="margin-top: 24px;">

    <div class="card-header">

        <div>
            <h2>Evaluated Positions</h2>

            <p>
                Latest evaluation information.
            </p>
        </div>

    </div>


    @if($evaluations->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Position</th>
                        <th>Score</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($evaluations as $evaluation)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <strong>
                                    {{ $evaluation->position->title ?? 'N/A' }}
                                </strong>

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

            <h3>No Evaluations Yet</h3>

            <p>
                Complete a position evaluation to see statistics here.
            </p>

        </div>

    @endif

</div>

@endsection


@push('styles')
<style>

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 22px;
}

.stat-label {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 30px;
    font-weight: 700;
}

.stat-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-top: 4px;
}

@media (max-width: 900px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

</style>
@endpush
