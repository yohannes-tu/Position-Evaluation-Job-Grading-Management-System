@extends('layouts.app')

@section('title', 'Evaluation Result')

@section('content')

<div class="evaluation-page">

    <div class="evaluation-header">

        <div>
            <h1>Evaluation Result</h1>

            <p>
                Evaluation result for
                <strong>{{ $position->title ?? $position->name }}</strong>
            </p>
        </div>

        <a
            href="{{ route('positions.show', $position) }}"
            class="evaluation-btn evaluation-btn-secondary"
        >
            ← Back to Position
        </a>

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Total Score --}}

    <div class="evaluation-position-card">

        <div class="evaluation-position-header">

            <h2>
                Overall Evaluation Score
            </h2>

        </div>

        <div style="padding: 30px; text-align: center;">

            <div style="
                font-size: 48px;
                font-weight: 700;
                color: #1e3a5f;
            ">
                {{ number_format($evaluation->total_score, 2) }}
            </div>

            <div style="
                color: #64748b;
                margin-top: 5px;
            ">
            @php
    $score = (float) $evaluation->total_score;

    if ($score >= 90) {
        $rating = 'Excellent';
    } elseif ($score >= 80) {
        $rating = 'Very Good';
    } elseif ($score >= 70) {
        $rating = 'Good';
    } elseif ($score >= 60) {
        $rating = 'Fair';
    } else {
        $rating = 'Needs Improvement';
    }
@endphp

<div style="
    margin-top: 15px;
    font-size: 20px;
    font-weight: 600;
">
    Rating: {{ $rating }}
</div>
    
                Total Score / 100
            </div>

            <div style="
                margin-top: 15px;
                font-weight: 600;
            ">
                Status:
                {{ ucfirst($evaluation->status) }}
            </div>

            

        </div>

    </div>

    <div class="evaluation-summary-grid">

    <div class="summary-card">
        <span class="summary-label">Position</span>
        <strong>
            {{ $position->title ?? $position->name }}
        </strong>
    </div>

    <div class="summary-card">
        <span class="summary-label">Evaluated By</span>
        <strong>
            {{ $evaluation->evaluator->name ?? 'N/A' }}
        </strong>
    </div>

    <div class="summary-card">
        <span class="summary-label">Evaluation Date</span>
        <strong>
            {{ $evaluation->evaluated_at?->format('M d, Y H:i') ?? 'N/A' }}
        </strong>
    </div>

    <div class="summary-card">
        <span class="summary-label">Status</span>
        <strong>
            {{ ucfirst($evaluation->status) }}
        </strong>
    </div>

    <div class="score-summary-card">

    <div class="score-summary-item">
        <span class="label">Total Score</span>

        <strong>
            {{ number_format($evaluation->total_score, 2) }}
        </strong>
    </div>

    <div class="score-summary-item">
        <span class="label">Recommended Grade</span>

        <strong>
            {{ $grade?->name ?? 'No matching grade' }}
        </strong>
    </div>

</div>

</div>


    {{-- Factor Breakdown --}}

    <div class="evaluation-factor-card">

        <div class="evaluation-factor-header">

            <div class="factor-title-area">

                <h2>
                    Evaluation Breakdown
                </h2>

                <p class="factor-description">
                    Detailed score for each evaluation factor.
                </p>

            </div>

        </div>


        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>Factor</th>
                        <th>Criterion</th>
                        <th>Score</th>
                        <th>Weight</th>
                        <th>Weighted Score</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($evaluation->factors as $factor)

                        <tr>

                            <td>
                                <strong>
                                    {{ $factor->evaluationFactor->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $factor->criterion->name }}
                            </td>

                            <td>
                                {{ number_format($factor->score, 2) }}
                            </td>

                            <td>
                                {{ number_format($factor->weight, 2) }}%
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($factor->weighted_score, 2) }}
                                </strong>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="4" style="text-align:right;">
                            Total
                        </th>

                        <th>
                            {{ number_format($evaluation->total_score, 2) }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

    <div class="card" style="margin-top: 20px;">

    <div class="card-header">
        <div>
            <h2>Factor Analysis</h2>

            <p>
                Detailed breakdown of the position evaluation.
            </p>
        </div>
    </div>

    <div class="table-container">

        <table class="data-table">

           <thead>
    <tr>
        <th>#</th>
        <th>Factor</th>
        <th>Weight</th>
        <th>Criterion</th>
        <th>Score</th>
        <th>Weighted Score</th>
        <th>Contribution</th>
    </tr>
</thead>
            <tbody>

                @foreach($evaluation->factors as $evaluationFactor)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $evaluationFactor->evaluationFactor->name ?? 'N/A' }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format((float) $evaluationFactor->weight, 2) }}%
                        </td>
                        <td>

    @php
        $totalScore = (float) $evaluation->total_score;

        $contribution = $totalScore > 0
            ? ((float) $evaluationFactor->weighted_score / $totalScore) * 100
            : 0;
    @endphp

    {{ number_format($contribution, 1) }}%

</td>

                        <td>

                            @if($evaluationFactor->criterion)

                                <strong>
                                    {{ $evaluationFactor->criterion->name }}
                                </strong>

                                @if($evaluationFactor->criterion->description)

                                    <div style="
                                        color: #64748b;
                                        font-size: 13px;
                                        margin-top: 4px;
                                    ">
                                        {{ $evaluationFactor->criterion->description }}
                                    </div>

                                @endif

                            @else

                                <span style="color:#94a3b8;">
                                    No criterion selected
                                </span>

                            @endif

                        </td>

                        <td>
                            <strong>
                                {{ number_format((float) $evaluationFactor->score, 2) }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ number_format((float) $evaluationFactor->weighted_score, 2) }}
                            </strong>
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="card" style="margin-top: 20px;">

    <div class="card-header">
        <div>
            <h2>Factor Performance</h2>

            <p>
                Visual representation of each factor's contribution.
            </p>
        </div>
    </div>

    <div style="padding: 20px;">

        @foreach($evaluation->factors as $evaluationFactor)

            @php
                $maxScore = $evaluationFactor->evaluationFactor->max_score ?? 100;

                $percentage = $maxScore > 0
                    ? ((float) $evaluationFactor->score / (float) $maxScore) * 100
                    : 0;

                $percentage = min(max($percentage, 0), 100);
                $percentageValue = number_format((float) $percentage, 1, '.', '');
            @endphp

            <div style="margin-bottom: 22px;">

                <div style="
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 7px;
                ">

                    <strong>
                        {{ $evaluationFactor->evaluationFactor->name ?? 'N/A' }}
                    </strong>

                    <span>
                        {{ $percentageValue }}%
                    </span>

                </div>

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

    </div>

</div>

</div>

<div style="
    margin-top: 25px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
">

    <a
        href="{{ route('positions.evaluate', ['position' => $position->id]) }}"
        class="evaluation-btn evaluation-btn-primary"
    >
        Evaluate Again
    </a>

    <a
        href="{{ route('positions.show', ['position' => $position->id]) }}"
        class="evaluation-btn evaluation-btn-secondary"
    >
        Back to Position
    </a>

</div>

@endsection


@push('styles')

<style>

.evaluation-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin: 20px 0;
}

.summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 20px;
}

.summary-label {
    display: block;
    color: #64748b;
    font-size: 13px;
    margin-bottom: 8px;
}

.summary-card strong {
    display: block;
    color: #1e293b;
    font-size: 16px;
}

@media (max-width: 900px) {

    .evaluation-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .evaluation-summary-grid {
        grid-template-columns: 1fr;
    }

}

</style>

@endpush
