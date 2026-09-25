@extends('layouts.app')

@section('title', 'Position Evaluation Report')

@section('content')

<div class="page-header">
    <div>
        <h1>Position Evaluation Report</h1>
        <p>
            View detailed evaluation results for an organizational position.
        </p>
    </div>

    <a href="{{ route('reports.index') }}" class="btn">
        ← Reports
    </a>
</div>


{{-- Position Selection --}}

<div class="card">

    <div class="card-header">
        <h2>Select Position</h2>
    </div>

    <form method="GET"
          action="{{ route('reports.position-evaluation') }}"
          class="report-filter-form">

        <div class="form-group">

            <label for="position_id">
                Position
            </label>

            <select
                name="position_id"
                id="position_id"
                required
            >

                <option value="">
                    -- Select Position --
                </option>

                @foreach ($positions as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ request('position_id') == $item->id ? 'selected' : '' }}
                    >
                        {{ $item->title }}
                        @if($item->code)
                            ({{ $item->code }})
                        @endif
                    </option>

                @endforeach

            </select>

        </div>

        <button type="submit" class="btn btn-primary">
            Generate Report
        </button>

    </form>

</div>


@if($position && $evaluation)

<div class="card evaluation-report">

    {{-- Report Header --}}

    <div class="report-title">

        <div>
            <h2>POSITION EVALUATION REPORT</h2>
            <p>Position Evaluation & Job Grading Management System</p>
        </div>

        <div class="report-date">
            {{ now()->format('d M Y') }}
        </div>

    </div>


    {{-- Position Information --}}

    <div class="report-section">

        <h3>Position Information</h3>

        <div class="report-info-grid">

            <div>
                <span>Position Title</span>
                <strong>{{ $position->title }}</strong>
            </div>

            <div>
                <span>Position Code</span>
                <strong>{{ $position->code ?? 'N/A' }}</strong>
            </div>

            <div>
                <span>Department</span>
                <strong>
                    {{ $position->department->name ?? 'N/A' }}
                </strong>
            </div>

            <div>
                <span>Employment Type</span>
                <strong>
                    {{ $position->employment_type ?? 'N/A' }}
                </strong>
            </div>

            <div>
                <span>Education</span>
                <strong>
                    {{ $position->education ?? 'N/A' }}
                </strong>
            </div>

            <div>
                <span>Experience</span>
                <strong>
                    {{ $position->experience ?? 'N/A' }}
                </strong>
            </div>

        </div>

    </div>


    {{-- Evaluation Information --}}

    <div class="report-section">

        <h3>Evaluation Information</h3>

        <div class="report-info-grid">

            <div>
                <span>Status</span>

                <strong>
                    {{ ucfirst($evaluation->status) }}
                </strong>
            </div>

            <div>
                <span>Evaluator</span>

                <strong>
                    {{ $evaluation->evaluator->name ?? 'N/A' }}
                </strong>
            </div>

            <div>
                <span>Evaluation Date</span>

                <strong>
                    {{ $evaluation->evaluated_at
                        ? $evaluation->evaluated_at->format('d M Y')
                        : $evaluation->created_at->format('d M Y')
                    }}
                </strong>
            </div>

        </div>

    </div>


    {{-- Factor Breakdown --}}

    <div class="report-section">

        <h3>Evaluation Factor Breakdown</h3>

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
                    </tr>

                </thead>

                <tbody>

                    @forelse($evaluation->factors as $index => $factor)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <strong>
                                {{ $factor->evaluationFactor->name ?? 'N/A' }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format((float) $factor->weight, 2) }}%
                        </td>

                        <td>
                            {{ $factor->criterion->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ number_format((float) $factor->score, 2) }}
                        </td>

                        <td>
                            <strong>
                                {{ number_format((float) $factor->weighted_score, 2) }}
                            </strong>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No evaluation factor records found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Score Summary --}}

    <div class="score-summary">

        <div class="score-box">

            <span>Total Score</span>

            <strong>
                {{ number_format((float) $evaluation->total_score, 2) }}
            </strong>

            <small>
                / 100
            </small>

        </div>

        @php
            $grade = \App\Models\Grade::where('is_active', true)
                ->where('min_score', '<=', $evaluation->total_score)
                ->where('max_score', '>=', $evaluation->total_score)
                ->first();
        @endphp

        <div class="score-box">

            <span>Recommended Grade</span>

            <strong>
                {{ $grade->name ?? 'Not Assigned' }}
            </strong>

        </div>

    </div>


    {{-- Signatures --}}

    <div class="signature-section">

        <div class="signature-box">
            <span>Evaluator</span>
            <div></div>
            <small>Signature / Date</small>
        </div>

        <div class="signature-box">
            <span>Committee Chair</span>
            <div></div>
            <small>Signature / Date</small>
        </div>

        <div class="signature-box">
            <span>HR Manager</span>
            <div></div>
            <small>Signature / Date</small>
        </div>

        <div class="signature-box">
            <span>Authorized Approver</span>
            <div></div>
            <small>Signature / Date</small>
        </div>

    </div>


    {{-- Print --}}

    <div class="report-actions">

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary"
        >
            🖨 Print Report
        </button>

    </div>

</div>


@elseif(request()->filled('position_id'))

<div class="card">

    <div class="empty-state">

        <div class="empty-icon">
            📋
        </div>

        <h3>No Evaluation Found</h3>

        <p>
            The selected position does not have an evaluation yet.
        </p>

        <a
            href="{{ route('positions.index') }}"
            class="btn btn-primary"
        >
            View Positions
        </a>

    </div>

</div>

@endif

@endsection