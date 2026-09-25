@extends('layouts.app')

@section('title', 'Evaluation Criteria')

@section('content')

<div class="page-header">

    <div>
        <h1>{{ $evaluation_factor->name }} Criteria</h1>

        <p>
            Define the scoring criteria for this evaluation factor.
        </p>
    </div>

    <div>

        <a
            href="{{ route('evaluation-factors.index') }}"
            class="btn"
        >
            ← Back
        </a>

        <a
            href="{{ route('evaluation-factors.criteria.create', $evaluation_factor) }}"
            class="btn btn-primary"
        >
            + Add Criterion
        </a>

    </div>

</div>


@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="card">

    <div class="card-header">

        <h2>
            {{ $evaluation_factor->name }}
        </h2>

        <p>
            Weight:
            <strong>{{ $evaluation_factor->weight }}%</strong>
        </p>

    </div>


    @if ($criteria->count() > 0)

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Criterion</th>
                        <th>Description</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($criteria as $criterion)

                        <tr>

                            <td>
                                {{ $criterion->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ $criterion->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $criterion->description ?? 'No description' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $criterion->points }}
                                </strong>
                            </td>

                            <td>

                                <a
                                    href="{{ route('evaluation-factors.criteria.edit', [$evaluation_factor, $criterion]) }}"
                                    class="btn btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('evaluation-factors.criteria.destroy', [$evaluation_factor, $criterion]) }}"
                                    style="display:inline"
                                    onsubmit="return confirm('Delete this criterion?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm"
                                    >
                                        Delete
                                    </button>

                                </form>

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

            <h3>No Criteria Yet</h3>

            <p>
                Add scoring criteria for {{ $evaluation_factor->name }}.
            </p>

            <a
                href="{{ route('evaluation-factors.criteria.create', $evaluation_factor) }}"
                class="btn btn-primary"
            >
                + Add First Criterion
            </a>

        </div>

    @endif

</div>

@endsection