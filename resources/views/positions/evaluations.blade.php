@extends('layouts.app')

@section('title', 'Evaluation History')

@section('content')

<div class="page-header">

    <div>
        <h1>Evaluation History</h1>

        <p>
            Previous evaluations for
            <strong>
                {{ $position->title ?? $position->name }}
            </strong>
        </p>
    </div>

    <div style="display:flex; gap:10px;">

        <a
            href="{{ route('positions.evaluate', ['position' => $position->id]) }}"
            class="evaluation-btn evaluation-btn-primary"
        >
            + New Evaluation
        </a>

        <a
            href="{{ route('positions.show', ['position' => $position->id]) }}"
            class="evaluation-btn evaluation-btn-secondary"
        >
            ← Back
        </a>

    </div>

</div>

@if($latestEvaluation)

    <div class="evaluation-overview">

        <div class="overview-card">

            <span class="overview-label">
                Latest Score
            </span>

            <strong class="overview-score">
                {{ number_format($latestEvaluation->total_score, 2) }}
            </strong>

            <span class="overview-total">
                / 100
            </span>

        </div>


        <div class="overview-card">

            <span class="overview-label">
                Final Rating
            </span>

            <strong class="overview-rating">
                {{ $latestRating }}
            </strong>

        </div>


        <div class="overview-card">

            <span class="overview-label">
                Evaluated By
            </span>

            <strong>
                {{ $latestEvaluation->evaluator->name ?? 'N/A' }}
            </strong>



        </div>


        <div class="overview-card">

            <span class="overview-label">
                Evaluation Date
            </span>

            <strong>
                {{ $latestEvaluation->created_at?->format('M d, Y') }}
            </strong>
            @if($ranking)

    <div class="overview-card">

        <span class="overview-label">
            Position Rank
        </span>

        <strong class="overview-score">
            #{{ $ranking }}
        </strong>

    </div>

@endif

        </div>

    </div>

@endif


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="card">

    <div class="card-header">

        <div>
            <h2>Evaluation History</h2>

            <p>
                {{ $evaluations->count() }}
                evaluation(s) found.
            </p>
        </div>

    </div>


    @if($evaluations->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Evaluated By</th>
                        <th>Total Score</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($evaluations as $evaluation)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $evaluation->created_at?->format('M d, Y H:i') }}
                            </td>

                            <td>
                                {{ $evaluation->evaluator->name ?? 'N/A' }}
                            </td>

                            <td>

                                <strong>
                                    {{ number_format($evaluation->total_score, 2) }}
                                </strong>

                                / 100

                            </td>
                            <td>
    <span class="rating-badge">
    {{ $evaluation->rating }}
</span>
</td>

                            <td>

                                @if($evaluation->status === 'completed')

                                    <span class="status-badge status-completed">
                                        Completed
                                    </span>

                                @else

                                    <span class="status-badge status-draft">
                                        Draft
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'positions.evaluation.show',
                                        [
                                            'position' => $position->id,
                                            'evaluation' => $evaluation->id
                                        ]
                                    ) }}"
                                    class="evaluation-btn evaluation-btn-secondary"
                                >
                                    View Result
                                </a>
                                <form
    action="{{ route(
        'positions.evaluation.destroy',
        [
            'position' => $position->id,
            'evaluation' => $evaluation->id
        ]
    ) }}"
    method="POST"
    style="display:inline;"
    onsubmit="return confirm('Are you sure you want to delete this evaluation? This action cannot be undone.');"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="evaluation-btn evaluation-btn-danger"
    >
        Delete
    </button>
</form>

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
                This position has not been evaluated yet.
            </p>

            <a
                href="{{ route('positions.evaluate', ['position' => $position->id]) }}"
                class="evaluation-btn evaluation-btn-primary"
            >
                + Evaluate Position
            </a>

        </div>

    @endif

</div>


<style>

.evaluation-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 15px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.evaluation-btn-primary {
    background: #2563eb;
    color: white;
}

.evaluation-btn-secondary {
    background: #e2e8f0;
    color: #1e293b;
}

.status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}
.evaluation-btn-danger {
    background: #fee2e2;
    color: #991b1b;
    border: none;
}

.evaluation-btn-danger:hover {
    background: #fecaca;
}

.evaluation-overview {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.overview-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 20px;
}

.overview-label {
    display: block;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
}

.overview-score {
    font-size: 28px;
}

.overview-total {
    color: #64748b;
    margin-left: 4px;
}

.overview-rating {
    font-size: 20px;
}

@media (max-width: 900px) {
    .evaluation-overview {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .evaluation-overview {
        grid-template-columns: 1fr;
    }
}

.rating-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
</style>

@endsection