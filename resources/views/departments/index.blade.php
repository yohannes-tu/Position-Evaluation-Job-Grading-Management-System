@extends('layouts.app')

@section('title', 'Departments')

@section('page-title', 'Departments')

@section('content')

    <div class="page-header">

        <div>
            <h2>Departments</h2>
            <p>Manage organizational departments.</p>
        </div>

        @if(auth()->user()->role === 'admin')

    <a href="{{ route('departments.create') }}" class="btn btn-primary">
          + Add Department
    </a>

@endif

    </div>


    @if(session('success'))

    <div class="alert-success">
        {{ session('success') }}
    </div>

@endif

@if(session('error'))

    <div class="alert-error">
        {{ session('error') }}
    </div>

@endif


    <div class="content-card">

        <div class="table-header">

            <div>
                <h3>Department List</h3>
                <span>
                    {{ $departments->total() }} departments
                </span>
            </div>

        </div>


        @if($departments->count())

            <div class="table-wrapper">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($departments as $department)

                            <tr>

                                <td>
                                    {{ $departments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $department->name }}
                                    </strong>

                                    @if($department->description)
                                        <small>
                                            {{ $department->description }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <span class="code-badge">
                                        {{ $department->code }}
                                    </span>
                                </td>

                                <td>

                                    @if($department->is_active)

                                        <span class="status-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="status-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $department->created_at->format('M d, Y') }}
                                </td>

                                <td>

                                    @if(auth()->user()->role === 'admin')

    <div class="table-actions">

        <a
            href="{{ route('departments.edit', $department) }}"
            class="btn-edit">
            Edit
        </a>

        <form
            method="POST"
            action="{{ route('departments.destroy', $department) }}"
            onsubmit="return confirm('Are you sure you want to delete this department?');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn-delete">
                Delete
            </button>

        </form>

    </div>

@else

    <span class="view-only">
        View only
    </span>

@endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="pagination">
                {{ $departments->links() }}
            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ▤
                </div>

                <h4>No departments found</h4>

                <p>
                    Create your first department to get started.
                </p>

                <a
                    href="{{ route('departments.create') }}"
                    class="btn-primary">
                    + Add Department
                </a>

            </div>

        @endif

    </div>

@endsection