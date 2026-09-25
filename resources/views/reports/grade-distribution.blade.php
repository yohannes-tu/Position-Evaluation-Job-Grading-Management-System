@extends('layouts.app')

@section('title', 'Grade Distribution Report')

@section('content')

<div class="page-header">

    <div>
        <h1>Grade Distribution Report</h1>

        <p>
            Analyze the distribution of evaluated positions across grades.
        </p>
    </div>

    <a
        href="{{ route('reports.index') }}"
        class="btn"
    >
        ← Reports
    </a>

</div>


{{-- Filters --}}

<div class="card report-filter-card">

    <div class="card-header">
        <h2>Report Filters</h2>
    </div>

    <form
        method="GET"
        action="{{ route('reports.grade-distribution') }}"
        class="inventory-filters"
    >

        <div class="form-group">

            <label for="department_id">
                Department
            </label>

            <select
                name="department_id"
                id="department_id"
            >

                <option value="">
                    All Departments
                </option>

                @foreach($departments as $department)

                    <option
                        value="{{ $department->id }}"
                        {{ request('department_id') == $department->id ? 'selected' : '' }}
                    >
                        {{ $department->name }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="filter-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Apply Filter
            </button>

            <a
                href="{{ route('reports.grade-distribution') }}"
                class="btn"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Summary --}}

<div class="dashboard-grid">

    <div class="dashboard-card">

        <div class="dashboard-card-label">
            Evaluated Positions
        </div>

        <div class="dashboard-card-value">
            {{ $totalEvaluated }}
        </div>

    </div>


    <div class="dashboard-card">

        <div class="dashboard-card-label">
            Active Grades
        </div>

        <div class="dashboard-card-value">
            {{ count($distribution) }}
        </div>

    </div>

</div>


{{-- Distribution --}}

<div class="card">

    <div class="inventory-report-header">

        <div>

            <h2>Grade Distribution</h2>

            <p>
                Distribution of completed position evaluations.
            </p>

        </div>

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary print-button"
        >
            🖨 Print
        </button>

    </div>


    @if(count($distribution) > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Grade</th>
                        <th>Score Range</th>
                        <th>Positions</th>
                        <th>Percentage</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($distribution as $index => $item)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item['grade']->name }}
                                </strong>

                            </td>

                            <td>
                                {{ number_format((float) $item['grade']->min_score, 2) }}
                                -
                                {{ number_format((float) $item['grade']->max_score, 2) }}
                            </td>

                            <td>
                                {{ $item['count'] }}
                            </td>

                            <td>

                                <div class="percentage-wrapper">

                                    <div class="percentage-bar">

                                        <div
                                            class="percentage-fill"
                                            <div class="percentage-fill w-[{{ min($item['percentage'], 100) }}%]"></div>
                                        ></div>

                                    </div>

                                    <span>
                                        {{ number_format($item['percentage'], 1) }}%
                                    </span>

                                </div>

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

            <h3>No Grade Data</h3>

            <p>
                There are no completed evaluations matching the selected filters.
            </p>

        </div>

    @endif

</div>


{{-- Detailed positions --}}

@if($totalEvaluated > 0)

<div class="card grade-details-card">

    <div class="card-header">

        <h2>Evaluated Positions</h2>

    </div>


    <div class="table-container">

        <table class="data-table">

            <thead>

                <tr>
                    <th>#</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Total Score</th>
                    <th>Grade</th>
                </tr>

            </thead>

            <tbody>

                @php
                    $rowNumber = 1;
                @endphp

                @foreach($distribution as $item)

                    @foreach($item['evaluations'] as $evaluation)

                        <tr>

                            <td>
                                {{ $rowNumber++ }}
                            </td>

                            <td>

                                <strong>
                                    {{ $evaluation->position->title ?? 'N/A' }}
                                </strong>

                                @if($evaluation->position?->code)

                                    <small class="table-subtext">
                                        {{ $evaluation->position->code }}
                                    </small>

                                @endif

                            </td>

                            <td>
                                {{ $evaluation->position->department->name ?? 'N/A' }}
                            </td>

                            <td>

                                <strong>
                                    {{ number_format((float) $evaluation->total_score, 2) }}
                                </strong>

                            </td>

                            <td>

                                <span class="grade-badge">
                                    {{ $item['grade']->name }}
                                </span>

                            </td>

                        </tr>

                    @endforeach

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endif

@endsection