@extends('layouts.app')

@section('title', 'Evaluation Progress Report')

@section('content')

<div class="page-header">

    <div>
        <h1>Evaluation Progress Report</h1>

        <p>
            Monitor the progress and status of position evaluations.
        </p>
    </div>

    <a
        href="{{ route('reports.index') }}"
        class="btn"
    >
        ← Reports
    </a>

</div>


{{-- Summary --}}

<div class="dashboard-grid">

    <div class="dashboard-card">
        <div class="dashboard-card-label">
            Total Evaluations
        </div>

        <div class="dashboard-card-value">
            {{ $total }}
        </div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-label">
            Draft
        </div>

        <div class="dashboard-card-value">
            {{ $draft }}
        </div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-label">
            Completed
        </div>

        <div class="dashboard-card-value">
            {{ $completed }}
        </div>
    </div>

</div>


{{-- Filters --}}

<div class="card report-filter-card">

    <div class="card-header">
        <h2>Filters</h2>
    </div>

    <form
        method="GET"
        action="{{ route('reports.evaluation-progress') }}"
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


        <div class="form-group">

            <label for="status">
                Evaluation Status
            </label>

            <select name="status" id="status">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="draft"
                    {{ request('status') === 'draft' ? 'selected' : '' }}
                >
                    Draft
                </option>

                <option
                    value="completed"
                    {{ request('status') === 'completed' ? 'selected' : '' }}
                >
                    Completed
                </option>

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
                href="{{ route('reports.evaluation-progress') }}"
                class="btn"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Evaluation table --}}

<div class="card">

    <div class="inventory-report-header">

        <div>
            <h2>Evaluation Progress</h2>
        </div>

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary print-button"
        >
            🖨 Print
        </button>

    </div>


    @if($evaluations->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Evaluator</th>
                        <th>Total Score</th>
                        <th>Status</th>
                        <th>Evaluated At</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($evaluations as $evaluation)

                        <tr>

                            <td>
                                {{ $evaluations->firstItem() + $loop->index }}
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
                                {{ $evaluation->evaluator->name ?? 'Not assigned' }}
                            </td>

                            <td>
                                {{ number_format((float) $evaluation->total_score, 2) }}
                            </td>

                            <td>

                                @if($evaluation->status === 'completed')

                                    <span class="status-badge status-approved">
                                        Completed
                                    </span>

                                @else

                                    <span class="status-badge status-pending">
                                        Draft
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($evaluation->evaluated_at)

                                    {{ $evaluation->evaluated_at->format('d M Y H:i') }}

                                @else

                                    —

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <div class="pagination-container">

            {{ $evaluations->links() }}

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                📊
            </div>

            <h3>No Evaluations Found</h3>

            <p>
                No evaluations match the selected filters.
            </p>

        </div>

    @endif

</div>

@endsection