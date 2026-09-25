@extends('layouts.app')

@section('content')

<div class="page-container">

    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h1>Positions</h1>

            <p>
                Manage organizational job positions.
            </p>
        </div>

        @if(auth()->user()->role === 'admin')

            <a
                href="{{ route('positions.create') }}"
                class="btn-primary">
                + Add Position
            </a>

        @endif

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert-error">
            {{ session('error') }}
        </div>

    @endif


    {{-- Position Table --}}
    <div class="card">

        <div class="card-header">

            <h2>Position List</h2>

            <span class="record-count">
                {{ $positions->total() }} positions
            </span>

        </div>


        @if($positions->count() > 0)

            <div class="table-responsive">

                <table class="data-table">

                    <thead>

                        <tr>
                            <th>Code</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Employment Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($positions as $position)

                            <tr>

                                {{-- Code --}}
                                <td>
                                    <span class="position-code">
                                        {{ $position->code }}
                                    </span>
                                </td>


                                {{-- Title --}}
                                <td>

                                    <div class="position-title">
                                        {{ $position->title }}
                                    </div>

                                    @if($position->description)

                                        <div class="position-description">

                                            {{ Str::limit($position->description, 70) }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Department --}}
                                <td>

                                    @if($position->department)

                                        {{ $position->department->name }}

                                    @else

                                        <span class="text-muted">
                                            No department
                                        </span>

                                    @endif

                                </td>


                                {{-- Employment Type --}}
                                <td>

                                    @switch($position->employment_type)

                                        @case('full_time')
                                            Full Time
                                            @break

                                        @case('part_time')
                                            Part Time
                                            @break

                                        @case('contract')
                                            Contract
                                            @break

                                        @default
                                            Unknown

                                    @endswitch

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($position->is_active)

                                        <span class="status-badge status-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="{{ route('positions.show', $position) }}"
                                            class="btn-view">
                                            View
                                        </a>


                                        @if(auth()->user()->role === 'admin')

                                            <a
                                                href="{{ route('positions.edit', $position) }}"
                                                class="btn-edit">
                                                Edit
                                            </a>

                                            <a
                                                href="{{ route('positions.evaluate', $position) }}"
                                                class="btn btn-sm btn-primary"
                                                  >
                                                         Evaluate
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('positions.destroy', $position) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this position?');">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-delete">
                                                    Delete
                                                </button>

                                            </form>

                                        @endif

                                    </div>

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

            {{-- Empty State --}}
            <div class="empty-state">

                <div class="empty-icon">
                    📋
                </div>

                <h3>No positions found</h3>

                <p>
                    There are currently no positions registered in the system.
                </p>

                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('positions.create') }}"
                        class="btn-primary">
                        + Add First Position
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection