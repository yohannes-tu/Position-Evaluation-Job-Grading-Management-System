@extends('layouts.app')

@push('styles')
<style>
    /* =========================================
       POSITION EVALUATION PAGE
       ========================================= */

    .evaluation-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header */

    .evaluation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .evaluation-header h1 {
        margin: 0 0 6px;
        font-size: 32px;
        font-weight: 700;
    }

    .evaluation-header p {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    /* =========================================
       POSITION INFORMATION
       ========================================= */

    .evaluation-position-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .evaluation-position-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .evaluation-position-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 650;
    }

    .evaluation-position-body {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        padding: 22px;
    }

    .position-info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .position-info-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .position-info-value {
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
    }

    /* =========================================
       FACTOR CARD
       ========================================= */

    .evaluation-factor-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .evaluation-factor-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 20px 22px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .factor-title-area {
        min-width: 0;
    }

    .factor-title-area h2 {
        margin: 0 0 6px;
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }

    .factor-description {
        margin: 0;
        color: #64748b;
        line-height: 1.5;
        font-size: 14px;
    }

    .factor-weight {
        flex-shrink: 0;
        background: #e0ecff;
        color: #1d4ed8;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
    }

    /* =========================================
       CRITERIA
       ========================================= */

    .evaluation-criteria {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .criterion-option {
        display: grid;
        grid-template-columns: 24px minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        width: 100%;
        padding: 15px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
        cursor: pointer;
        transition: all .15s ease;
        box-sizing: border-box;
    }

    .criterion-option:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .criterion-option input[type="radio"] {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
    }

    .criterion-option:has(input[type="radio"]:checked) {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .criterion-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .criterion-name {
        font-size: 15px;
        font-weight: 650;
        color: #1e293b;
    }

    .criterion-description {
        font-size: 13px;
        line-height: 1.45;
        color: #64748b;
    }

    .criterion-points {
        white-space: nowrap;
        padding: 6px 10px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    /* =========================================
       EMPTY CRITERIA
       ========================================= */

    .no-criteria {
        padding: 25px;
        text-align: center;
        color: #64748b;
    }

    /* =========================================
       ACTIONS
       ========================================= */

    .evaluation-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-bottom: 30px;
    }

    .evaluation-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 18px;
        border-radius: 8px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .evaluation-btn-secondary {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #334155;
    }

    .evaluation-btn-secondary:hover {
        background: #f8fafc;
    }

    .evaluation-btn-primary {
        background: #1e3a5f;
        color: #ffffff;
    }

    .evaluation-btn-primary:hover {
        background: #172f4d;
    }

    /* =========================================
       RESPONSIVE
       ========================================= */

    @media (max-width: 768px) {

        .evaluation-page {
            padding: 0 10px;
        }

        .evaluation-header {
            flex-direction: column;
        }

        .evaluation-position-body {
            grid-template-columns: 1fr;
        }

        .evaluation-factor-header {
            flex-direction: column;
        }

        .factor-weight {
            align-self: flex-start;
        }

        .criterion-option {
            grid-template-columns: 24px minmax(0, 1fr);
        }

        .criterion-points {
            grid-column: 2;
            justify-self: start;
        }

        .evaluation-actions {
            flex-direction: column-reverse;
        }

        .evaluation-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('title', 'Evaluate Position')

@section('content')

<div class="evaluation-page">

    {{-- =========================================
         PAGE HEADER
         ========================================= --}}

    <div class="evaluation-header">

        <div>
            <h1>Evaluate Position</h1>

            <p>
                Evaluate this position using the organization's evaluation factors.
            </p>
        </div>

        <a
            href="{{ route('positions.show', $position) }}"
            class="evaluation-btn evaluation-btn-secondary"
        >
            ← Back to Position
        </a>

    </div>


    {{-- =========================================
         POSITION INFORMATION
         ========================================= --}}

    <div class="evaluation-position-card">

        <div class="evaluation-position-header">
            <h2>Position Information</h2>
        </div>

        <div class="evaluation-position-body">

            <div class="position-info-item">

                <span class="position-info-label">
                    Position
                </span>

                <span class="position-info-value">
                    {{ $position->title ?? $position->name }}
                </span>

            </div>


            @if(isset($position->department))

                <div class="position-info-item">

                    <span class="position-info-label">
                        Department
                    </span>

                    <span class="position-info-value">
                        {{ $position->department->name }}
                    </span>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================
         EVALUATION FORM
         ========================================= --}}

    <form
    method="POST"
    action="{{ route('positions.evaluate.store', $position) }}"
>
    

        @csrf


        @foreach ($factors as $factor)

            <div class="evaluation-factor-card">

                {{-- Factor Header --}}

                <div class="evaluation-factor-header">

                    <div class="factor-title-area">

                        <h2>
                            {{ $factor->name }}
                        </h2>

                        @if($factor->description)

                            <p class="factor-description">
                                {{ $factor->description }}
                            </p>

                        @endif

                    </div>


                    <div class="factor-weight">

                        Weight:
                        {{ number_format($factor->weight, 2) }}%

                    </div>

                </div>


                {{-- Criteria --}}

                @if($factor->criteria->count() > 0)

                    <div class="evaluation-criteria">

                        @foreach ($factor->criteria as $criterion)

                            <label class="criterion-option">

                                <input
                                    type="radio"
                                    name="factor_{{ $factor->id }}"
                                    value="{{ $criterion->id }}"
                                    required
                                >


                                <div class="criterion-content">

                                    <span class="criterion-name">
                                        {{ $criterion->name }}
                                    </span>

                                    @if($criterion->description)

                                        <span class="criterion-description">
                                            {{ $criterion->description }}
                                        </span>

                                    @endif

                                </div>


                                <span class="criterion-points">

                                    {{ number_format($criterion->points, 2) }}

                                    points

                                </span>

                            </label>

                        @endforeach

                    </div>

                @else

                    <div class="no-criteria">

                        No criteria have been configured for this factor.

                    </div>

                @endif

            </div>

        @endforeach


        {{-- =========================================
             ACTION BUTTONS
             ========================================= --}}

        @if($factors->count() > 0)

            <div class="evaluation-actions">

                <a
                    href="{{ route('positions.show', $position) }}"
                    class="evaluation-btn evaluation-btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="evaluation-btn evaluation-btn-primary"
                >
                    Calculate Score
                </button>

            </div>

        @endif

    </form>

</div>

@endsection