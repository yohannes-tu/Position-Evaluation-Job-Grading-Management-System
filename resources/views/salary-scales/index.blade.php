@extends('layouts.app')

@section('title', 'Salary Scales')

@section('content')

<div class="page-header">
    <div>
        <h1>Salary Scales</h1>
        <p>Manage salary ranges associated with organizational grades.</p>
    </div>

    <div>
        <a href="{{ route('salary-scales.create') }}" class="btn-primary">
            + Add Salary Scale
        </a>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-error">
        <strong>Please correct the following errors:</strong>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <div>
            <h2>Salary Scale List</h2>
            <p>
                Configure minimum, midpoint, and maximum salaries for each grade.
            </p>
        </div>
    </div>

    @if($salaryScales->count())

        <div class="table-container">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Grade</th>
                        <th>Minimum Salary</th>
                        <th>Midpoint Salary</th>
                        <th>Maximum Salary</th>
                        <th>Currency</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($salaryScales as $salaryScale)

                        <tr>

                            <td>
                                {{ $salaryScales->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $salaryScale->grade->name ?? 'Grade ' . $salaryScale->grade_id }}
                                </strong>
                            </td>

                            <td>
                                {{ number_format($salaryScale->minimum_salary, 2) }}
                            </td>

                            <td>
                                {{ number_format($salaryScale->midpoint_salary, 2) }}
                            </td>

                            <td>
                                {{ number_format($salaryScale->maximum_salary, 2) }}
                            </td>

                            <td>
                                {{ $salaryScale->currency }}
                            </td>

                            <td>
                                {{ $salaryScale->effective_date
                                    ? \Carbon\Carbon::parse($salaryScale->effective_date)->format('M d, Y')
                                    : '—'
                                }}
                            </td>

                            <td>

                                @if($salaryScale->status === 'active')

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

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('salary-scales.show', $salaryScale) }}"
                                        class="btn-secondary btn-small"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('salary-scales.edit', $salaryScale) }}"
                                        class="btn-secondary btn-small"
                                    >
                                        Edit
                                    </a>

                                    @if($salaryScale->status === 'active')

                                        <form
                                            method="POST"
                                            action="{{ route('salary-scales.deactivate', $salaryScale) }}"
                                            onsubmit="return confirm('Are you sure you want to deactivate this salary scale?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn-warning btn-small"
                                            >
                                                Deactivate
                                            </button>
                                        </form>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('salary-scales.activate', $salaryScale) }}"
                                            onsubmit="return confirm('Are you sure you want to activate this salary scale?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn-success btn-small"
                                            >
                                                Activate
                                            </button>
                                        </form>

                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('salary-scales.destroy', $salaryScale) }}"
                                        onsubmit="return confirm('Are you sure you want to permanently delete this salary scale?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-danger btn-small"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="pagination-container">
            {{ $salaryScales->links() }}
        </div>

    @else

        <div class="empty-state">

            <div class="empty-state-icon">
                💰
            </div>

            <h3>No Salary Scales Found</h3>

            <p>
                No salary scales have been configured yet.
            </p>

            <a
                href="{{ route('salary-scales.create') }}"
                class="btn-primary"
            >
                + Add First Salary Scale
            </a>

        </div>

    @endif

</div>

@endsection