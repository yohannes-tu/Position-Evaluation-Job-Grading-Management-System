@extends('layouts.app')

@section('title', 'Edit Evaluation Factor')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Evaluation Factor</h1>
        <p>Update the evaluation factor information.</p>
    </div>

    <a href="{{ route('evaluation-factors.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

<div class="card">

    <div class="card-header">
        <h2>Factor Information</h2>
    </div>

    <form
        action="{{ route('evaluation-factors.update', $evaluationFactor) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        {{-- Validation Errors --}}
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

        <div class="form-group">

            <label for="name">
                Factor Name <span class="required">*</span>
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $evaluationFactor->name) }}"
                required
            >

            <small>
                Enter the name of the evaluation factor.
            </small>

        </div>

        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
            >{{ old('description', $evaluationFactor->description) }}</textarea>

            <small>
                Explain how this factor will be used when evaluating positions.
            </small>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label for="weight">
                    Weight (%)
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    id="weight"
                    name="weight"
                    value="{{ old('weight', $evaluationFactor->weight) }}"
                    min="0"
                    max="100"
                    step="0.01"
                    required
                >

                <small>
                    Importance of this factor as a percentage.
                </small>

            </div>

            <div class="form-group">

                <label for="max_score">
                    Maximum Score
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    id="max_score"
                    name="max_score"
                    value="{{ old('max_score', $evaluationFactor->max_score) }}"
                    min="1"
                    step="1"
                    required
                >

                <small>
                    Maximum points available for this factor.
                </small>

            </div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('evaluation-factors.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Factor
            </button>

        </div>

    </form>

</div>

@endsection