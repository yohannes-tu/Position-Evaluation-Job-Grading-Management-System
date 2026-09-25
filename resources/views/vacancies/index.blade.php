@extends('layouts.app')

@section('title', 'Vacancies')

@section('content')

<div class="page-header">

    <div>
        <h1>Vacancies</h1>
        <p>Manage recruitment opportunities and vacancy postings.</p>
    </div>

    <a href="{{ route('vacancies.create') }}"
       class="btn btn-primary">
        + Add Vacancy
    </a>

</div>


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


{{-- Filters --}}

<div class="filter-card">

    <form method="GET"
          action="{{ route('vacancies.index') }}"
          class="filter-form">

        <div class="filter-group">

            <label for="search">
                Search
            </label>

            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search vacancy code, position..."
            >

        </div>


        <div class="filter-group">

            <label for="status">
                Status
            </label>

            <select name="status" id="status">

                <option value="">
                    All Statuses
                </option>

                @foreach([
                    'draft' => 'Draft',
                    'pending_approval' => 'Pending Approval',
                    'approved' => 'Approved',
                    'published' => 'Published',
                    'closed' => 'Closed',
                    'cancelled' => 'Cancelled',
                ] as $value => $label)

                    <option
                        value="{{ $value }}"
                        @selected(request('status') === $value)
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="filter-actions">

            <button type="submit"
                    class="btn btn-primary">
                Search
            </button>

            <a href="{{ route('vacancies.index') }}"
               class="btn btn-secondary">
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Vacancy Table --}}

<div class="table-card">

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>Vacancy</th>

                    <th>Position</th>

                    <th>Department</th>

                    <th>Openings</th>

                    <th>Closing Date</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @forelse($vacancies as $vacancy)

                    <tr>

                        <td>

                            <strong>
                                {{ $vacancy->vacancy_code }}
                            </strong>

                        </td>


                        <td>

                            <div class="position-title">

                                {{ $vacancy->position->title ?? 'N/A' }}

                            </div>

                            <small>
                                {{ $vacancy->position->code ?? '' }}
                            </small>

                        </td>


                        <td>

                            {{ $vacancy->position->department->name ?? 'N/A' }}

                        </td>


                        <td>

                            {{ $vacancy->number_of_openings }}

                        </td>


                        <td>

                            @if($vacancy->closing_date)

                                {{ $vacancy->closing_date->format('M d, Y') }}

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            @switch($vacancy->status)

                                @case('draft')
                                    <span class="status status-draft">
                                        Draft
                                    </span>
                                    @break

                                @case('pending_approval')
                                    <span class="status status-pending">
                                        Pending Approval
                                    </span>
                                    @break

                                @case('approved')
                                    <span class="status status-approved">
                                        Approved
                                    </span>
                                    @break

                                @case('published')
                                    <span class="status status-published">
                                        Published
                                    </span>
                                    @break

                                @case('closed')
                                    <span class="status status-closed">
                                        Closed
                                    </span>
                                    @break

                                @case('cancelled')
                                    <span class="status status-cancelled">
                                        Cancelled
                                    </span>
                                    @break

                                @default
                                    <span class="status">
                                        {{ ucfirst($vacancy->status) }}
                                    </span>

                            @endswitch

                        </td>


                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('vacancies.show', $vacancy) }}"
                                    class="action-view"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route('vacancies.edit', $vacancy) }}"
                                    class="action-edit"
                                >
                                    Edit
                                </a>

                                @if($vacancy->status !== 'published')

                                    <form
                                        method="POST"
                                        action="{{ route('vacancies.destroy', $vacancy) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this vacancy?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-delete"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            <div class="empty-icon">
                                ▣
                            </div>

                            <h3>
                                No vacancies found
                            </h3>

                            <p>
                                There are currently no vacancies matching your search.
                            </p>

                            <a
                                href="{{ route('vacancies.create') }}"
                                class="btn btn-primary"
                            >
                                Create First Vacancy
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($vacancies->hasPages())

        <div class="pagination">

            {{ $vacancies->links() }}

        </div>

    @endif

</div>


@push('styles')

<style>

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #17365d;
}

.page-header p {
    margin: 5px 0 0;
    color: #64748b;
}


/* Buttons */

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 17px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid transparent;
}

.btn-primary {
    background: #173f6b;
    color: white;
}

.btn-primary:hover {
    background: #102f52;
}

.btn-secondary {
    background: white;
    border-color: #cbd5e1;
    color: #334155;
}


/* Alerts */

.alert {
    padding: 13px 17px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}


/* Filters */

.filter-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.filter-form {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 220px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}

.filter-group input,
.filter-group select {
    height: 42px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 0 12px;
    font-size: 14px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}


/* Table */

.table-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f8fafc;
}

th {
    padding: 14px 16px;
    text-align: left;
    font-size: 12px;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .04em;
}

td {
    padding: 15px 16px;
    border-top: 1px solid #e2e8f0;
    font-size: 14px;
    color: #334155;
}

.position-title {
    font-weight: 600;
    color: #173f6b;
}

td small {
    color: #94a3b8;
}


/* Status */

.status {
    display: inline-flex;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status-draft {
    background: #f1f5f9;
    color: #475569;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #dbeafe;
    color: #1e40af;
}

.status-published {
    background: #dcfce7;
    color: #166534;
}

.status-closed {
    background: #e2e8f0;
    color: #475569;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}


/* Actions */

.actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.actions a,
.actions button {
    border: none;
    background: none;
    padding: 0;
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
}

.action-view {
    color: #2563eb;
}

.action-edit {
    color: #475569;
}

.action-delete {
    color: #dc2626;
}


/* Empty */

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 35px;
    color: #94a3b8;
}

.empty-state h3 {
    margin: 10px 0 5px;
    color: #334155;
}

.empty-state p {
    color: #64748b;
    margin-bottom: 18px;
}


/* Pagination */

.pagination {
    padding: 15px 20px;
}


/* Responsive */

@media(max-width: 700px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        width: 100%;
        min-width: 0;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-actions .btn {
        flex: 1;
    }

}

</style>

@endpush

@endsection