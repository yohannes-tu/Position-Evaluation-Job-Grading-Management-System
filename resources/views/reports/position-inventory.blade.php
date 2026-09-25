@extends('layouts.app')

@section('title', 'Position Inventory Report')

@section('content')

<div class="page-header">

    <div>
        <h1>Position Inventory Report</h1>

        <p>
            View and filter the organization's complete position inventory.
        </p>
    </div>

    <div>
        <a
            href="{{ route('reports.index') }}"
            class="btn"
        >
            ← Reports
        </a>
    </div>

</div>


{{-- Filters --}}

<div class="card report-filter-card">

    <div class="card-header">
        <h2>Report Filters</h2>
    </div>

    <form
        method="GET"
        action="{{ route('reports.position-inventory') }}"
        class="inventory-filters"
    >

        <div class="form-group">

            <label for="search">
                Search
            </label>

            <input
                type="text"
                name="search"
                id="search"
                value="{{ request('search') }}"
                placeholder="Position title or code"
            >

        </div>


        <div class="form-group">

            <label for="department_id">
                Department
            </label>

            <select name="department_id" id="department_id">

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
                Status
            </label>

            <select name="status" id="status">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="active"
                    {{ request('status') === 'active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="inactive"
                    {{ request('status') === 'inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

        </div>


        <div class="filter-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('reports.position-inventory') }}"
                class="btn"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Report --}}

<div class="card inventory-report">

    <div class="inventory-report-header">

        <div>
            <h2>Position Inventory</h2>

            <p>
                Total results:
                <strong>{{ $positions->total() }}</strong>
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


    @if($positions->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Position</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Employment Type</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($positions as $position)

                        <tr>

                            <td>
                                {{ $positions->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $position->title }}
                                </strong>
                            </td>

                            <td>
                                {{ $position->code ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $position->department->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $position->employment_type ?? 'N/A' }}
                            </td>

                            <td>

                                @if($position->is_active)

                                    <span class="status-badge status-approved">
                                        Active
                                    </span>

                                @else

                                    <span class="status-badge status-inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $position->created_at->format('d M Y') }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        <div class="pagination-container">

            {{ $positions->links() }}

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                📋
            </div>

            <h3>No Positions Found</h3>

            <p>
                No positions match the selected filters.
            </p>

            <a
                href="{{ route('reports.position-inventory') }}"
                class="btn btn-primary"
            >
                Clear Filters
            </a>

        </div>

    @endif

</div>

@endsection