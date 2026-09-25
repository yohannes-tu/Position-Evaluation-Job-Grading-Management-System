@extends('layouts.app')

@section('title', 'Edit Evaluation Criterion')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Evaluation Criterion</h1>

        <p>
            Update the criterion for
            <strong>{{ $evaluation_factor->name }}</strong>.
        </p>
    </div>

    <a
        href="{{ route('evaluation-factors.criteria', $evaluation_factor) }}"
        class="btn btn-secondary"
    >
        ← Back to Criteria
    </a>
</div>


<div class="card">

    <div class="card-header">
        <h2>Criterion Information</h2>
    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'evaluation-factors.criteria.update',
            [$evaluation_factor, $criterion]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="form-group">

            <label for="name">
                Criterion Name
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $criterion->name) }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
            >{{ old('description', $criterion->description) }}</textarea>

        </div>


        <div class="form-group">

            <label for="points">
                Points
                <span class="required">*</span>
            </label>

            <input
                type="number"
                id="points"
                name="points"
                value="{{ old('points', $criterion->points) }}"
                min="0"
                max="100"
                step="0.01"
                required
            >

            <small>
                Enter a score between 0 and 100.
            </small>

        </div>


        <div class="form-actions">

            <a
                href="{{ route(
                    'evaluation-factors.criteria',
                    $evaluation_factor
                ) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Criterion
            </button>

        </div>

    </form>

</div>

@endsection