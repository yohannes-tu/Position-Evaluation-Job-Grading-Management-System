@extends('layouts.app')

@section('title', 'Evaluation Factors')

@section('content')

<div class="page-header">
    <div>
        <h1>Evaluation Factors</h1>
        <p>Manage the factors used to evaluate positions.</p>
    </div>

    <a href="{{ route('evaluation-factors.create') }}" class="btn btn-primary">
        + Add Factor
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">
        <h2>Evaluation Factors</h2>
    </div>

    @if ($factors->count() > 0)

        <div class="table-container">

            <table class="data-table">

     <thead>
    <tr>
        <th>#</th>
        <th>Factor Name</th>
        <th>Description</th>
        <th class="text-center">Weight</th>
        <th class="text-center">Max Score</th>
        <th class="text-center">Actions</th>
    </tr>
</thead>

                <tbody>

                    @foreach ($factors as $factor)

                     <tr>

    <td>
        {{ $factor->id }}
    </td>

    <td>
        <strong>{{ $factor->name }}</strong>
    </td>

    <td>
        {{ $factor->description ?? 'No description' }}
    </td>

    <td class="text-center">

        <span class="weight-badge">
            {{ number_format($factor->weight, 2) }}%
        </span>

    </td>

    <td class="text-center">

        <span class="score-badge">
            {{ $factor->max_score }}
        </span>

    </td>

    <td>

        <div class="action-buttons">

            <a
                href="{{ route('evaluation-factors.criteria', $factor) }}"
                class="btn btn-sm btn-secondary"
            >
                Criteria
            </a>

            <a
                href="{{ route('evaluation-factors.edit', $factor) }}"
                class="btn btn-sm btn-primary"
            >
                Edit
            </a>

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
                📋
            </div>

            <h3>No Evaluation Factors Yet</h3>

            <p>
                Add your first evaluation factor to begin evaluating positions.
            </p>

            <a
                href="{{ route('evaluation-factors.create') }}"
                class="btn btn-primary"
            >
                + Add First Factor
            </a>

        </div>

    @endif

</div>

@endsection