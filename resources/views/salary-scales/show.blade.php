@extends('layouts.app')

@section('title', 'Salary Scale Details')

@section('content')

<div class="page-header">
    <div>
        <h1>Salary Scale Details</h1>
        <p>View detailed information about this salary scale.</p>
    </div>

    <div class="header-actions">
        <a href="{{ route('salary-scales.index') }}" class="btn btn-secondary">
            ← Back to Salary Scales
        </a>

        <a href="{{ route('salary-scales.edit', $salaryScale) }}" class="btn btn-primary">
            ✎ Edit Salary Scale
        </a>
    </div>
</div>


{{-- Success Message --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<div class="details-container">

    {{-- Grade Header --}}
    <div class="grade-header">

        <div>
            <span class="section-label">GRADE</span>

            <h2>
                {{ $salaryScale->grade->name ?? 'N/A' }}
            </h2>

            <p>
                Salary scale information and compensation range
            </p>
        </div>

        <div>
            @if ($salaryScale->status === 'active')
                <span class="status-badge status-active">
                    Active
                </span>
            @else
                <span class="status-badge status-inactive">
                    Inactive
                </span>
            @endif
        </div>

    </div>


    {{-- Salary Summary --}}
    <div class="salary-summary">

        <div class="salary-card">
            <span class="salary-label">Minimum Salary</span>

            <strong>
                {{ $salaryScale->currency }}
                {{ number_format($salaryScale->minimum_salary, 2) }}
            </strong>

            <small>Starting salary</small>
        </div>


        <div class="salary-card highlight">
            <span class="salary-label">Midpoint Salary</span>

            <strong>
                {{ $salaryScale->currency }}
                {{ number_format($salaryScale->midpoint_salary, 2) }}
            </strong>

            <small>Reference midpoint</small>
        </div>


        <div class="salary-card">
            <span class="salary-label">Maximum Salary</span>

            <strong>
                {{ $salaryScale->currency }}
                {{ number_format($salaryScale->maximum_salary, 2) }}
            </strong>

            <small>Maximum salary</small>
        </div>

    </div>


    {{-- Salary Range --}}
    <div class="section-card">

        <div class="section-heading">
            <h3>Salary Range</h3>
            <span>
                {{ $salaryScale->currency }}
            </span>
        </div>

        <div class="range-container">

            <div class="range-labels">
                <div>
                    <span>Minimum</span>
                    <strong>
                        {{ number_format($salaryScale->minimum_salary, 2) }}
                    </strong>
                </div>

                <div class="range-middle">
                    <span>Midpoint</span>
                    <strong>
                        {{ number_format($salaryScale->midpoint_salary, 2) }}
                    </strong>
                </div>

                <div>
                    <span>Maximum</span>
                    <strong>
                        {{ number_format($salaryScale->maximum_salary, 2) }}
                    </strong>
                </div>
            </div>


            <div class="salary-range-bar">
                @php
                    $minimum = (float) $salaryScale->minimum_salary;
                    $midpoint = (float) $salaryScale->midpoint_salary;
                    $maximum = (float) $salaryScale->maximum_salary;

                    $range = $maximum - $minimum;

                    $midpointPercentage = $range > 0
                        ? (($midpoint - $minimum) / $range) * 100
                        : 50;
                @endphp

                <div
                    class="range-marker"
                    @php
    $min = $salaryScale->minimum_salary;
    $max = $salaryScale->maximum_salary;
    $mid = $salaryScale->midpoint_salary;
    $percentage = ($max > $min) ? (($mid - $min) / ($max - $min)) * 100 : 50;
@endphp

<div class="percentage-fill w-[{{ min(round($percentage), 100) }}%]"></div>
                >
                    <span>Midpoint</span>
                </div>
            </div>

        </div>

    </div>


    {{-- Salary Scale Information --}}
    <div class="section-card">

        <div class="section-heading">
            <h3>Salary Scale Information</h3>
        </div>

        <div class="information-grid">

            <div class="information-item">
                <span>Grade</span>
                <strong>
                    {{ $salaryScale->grade->name ?? 'N/A' }}
                </strong>
            </div>

            <div class="information-item">
                <span>Currency</span>
                <strong>
                    {{ $salaryScale->currency }}
                </strong>
            </div>

            <div class="information-item">
                <span>Effective Date</span>
                <strong>
                    {{ $salaryScale->effective_date
                        ? \Carbon\Carbon::parse($salaryScale->effective_date)->format('M d, Y')
                        : 'N/A'
                    }}
                </strong>
            </div>

            <div class="information-item">
                <span>Status</span>

                <strong>
                    @if ($salaryScale->status === 'active')
                        <span class="text-active">Active</span>
                    @else
                        <span class="text-inactive">Inactive</span>
                    @endif
                </strong>
            </div>

            <div class="information-item">
                <span>Created</span>
                <strong>
                    {{ $salaryScale->created_at
                        ? $salaryScale->created_at->format('M d, Y H:i')
                        : 'N/A'
                    }}
                </strong>
            </div>

            <div class="information-item">
                <span>Last Updated</span>
                <strong>
                    {{ $salaryScale->updated_at
                        ? $salaryScale->updated_at->format('M d, Y H:i')
                        : 'N/A'
                    }}
                </strong>
            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if ($salaryScale->notes)

        <div class="section-card">

            <div class="section-heading">
                <h3>Notes</h3>
            </div>

            <div class="notes">
                {{ $salaryScale->notes }}
            </div>

        </div>

    @endif


    {{-- Grade Evaluation Information --}}
    @if ($salaryScale->grade)

        <div class="section-card">

            <div class="section-heading">
                <h3>Grade Evaluation Range</h3>
            </div>

            <div class="grade-score">

                <div>
                    <span>Minimum Score</span>
                    <strong>
                        {{ number_format($salaryScale->grade->min_score, 2) }}
                    </strong>
                </div>

                <div class="score-separator">
                    →
                </div>

                <div>
                    <span>Maximum Score</span>
                    <strong>
                        {{ number_format($salaryScale->grade->max_score, 2) }}
                    </strong>
                </div>

            </div>

            <p class="help-text">
                This score range determines which positions are assigned to this grade
                during position evaluation.
            </p>

        </div>

    @endif


    {{-- Bottom Actions --}}
    <div class="bottom-actions">

        <a
            href="{{ route('salary-scales.index') }}"
            class="btn btn-secondary"
        >
            ← Back to Salary Scales
        </a>

        <a
            href="{{ route('salary-scales.edit', $salaryScale) }}"
            class="btn btn-primary"
        >
            ✎ Edit Salary Scale
        </a>

    </div>

</div>


@push('styles')

<style>

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #17365d;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #64748b;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .details-container {
        max-width: 1100px;
    }


    /* Buttons */

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary {
        background: #173f6b;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #102f52;
    }

    .btn-secondary {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #334155;
    }

    .btn-secondary:hover {
        background: #f8fafc;
    }


    /* Alert */

    .alert {
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }


    /* Grade Header */

    .grade-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 25px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .section-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }

    .grade-header h2 {
        margin: 0;
        font-size: 25px;
        color: #173f6b;
    }

    .grade-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }


    /* Status */

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #475569;
    }


    /* Salary Summary */

    .salary-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .salary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 22px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .salary-card.highlight {
        border-color: #93c5fd;
        background: #f8fbff;
    }

    .salary-label {
        display: block;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .salary-card strong {
        display: block;
        font-size: 23px;
        color: #173f6b;
    }

    .salary-card small {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
    }


    /* Sections */

    .section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 24px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .section-heading h3 {
        margin: 0;
        font-size: 18px;
        color: #17365d;
    }

    .section-heading span {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }


    /* Salary Range */

    .range-container {
        padding: 10px 5px 5px;
    }

    .range-labels {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        margin-bottom: 20px;
    }

    .range-labels > div {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .range-labels > div:nth-child(2) {
        align-items: center;
    }

    .range-labels > div:nth-child(3) {
        align-items: flex-end;
    }

    .range-labels span {
        color: #64748b;
        font-size: 13px;
    }

    .range-labels strong {
        color: #173f6b;
        font-size: 16px;
    }

    .salary-range-bar {
        position: relative;
        height: 12px;
        border-radius: 999px;
        background: #dbeafe;
        margin: 10px 0 25px;
    }

    .salary-range-bar::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        border-radius: inherit;
        background: #93c5fd;
    }

    .range-marker {
        position: absolute;
        top: -8px;
        transform: translateX(-50%);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #173f6b;
        border: 4px solid #ffffff;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }

    .range-marker span {
        position: absolute;
        top: 34px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-size: 12px;
        color: #64748b;
    }


    /* Information */

    .information-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .information-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .information-item span {
        font-size: 13px;
        color: #64748b;
    }

    .information-item strong {
        font-size: 15px;
        color: #1e293b;
    }

    .text-active {
        color: #15803d;
    }

    .text-inactive {
        color: #64748b;
    }


    /* Notes */

    .notes {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 15px;
        color: #334155;
        line-height: 1.6;
        white-space: pre-line;
    }


    /* Grade Score */

    .grade-score {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 35px;
        padding: 15px 0;
    }

    .grade-score > div:not(.score-separator) {
        text-align: center;
    }

    .grade-score span {
        display: block;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .grade-score strong {
        font-size: 22px;
        color: #173f6b;
    }

    .score-separator {
        font-size: 24px;
        color: #94a3b8;
    }

    .help-text {
        text-align: center;
        color: #64748b;
        font-size: 13px;
        margin: 15px 0 0;
    }


    /* Bottom Actions */

    .bottom-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 10px 0 30px;
    }


    /* Responsive */

    @media (max-width: 800px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .salary-summary {
            grid-template-columns: 1fr;
        }

        .information-grid {
            grid-template-columns: 1fr 1fr;
        }
    }


    @media (max-width: 550px) {

        .grade-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .information-grid {
            grid-template-columns: 1fr;
        }

        .range-labels {
            font-size: 12px;
        }

        .grade-score {
            gap: 15px;
        }

        .bottom-actions {
            flex-direction: column;
        }

        .bottom-actions .btn {
            width: 100%;
        }

    }

</style>

@endpush

@endsection