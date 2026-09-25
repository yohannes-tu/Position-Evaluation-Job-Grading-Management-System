@extends('layouts.app')

@section('title', 'Add Evaluation Criterion')

@section('content')

<div class="page-header">

    <div>
        <h1>Add Criterion</h1>

        <p>
            Add a scoring criterion under
            <strong>{{ $evaluation_factor->name }}</strong>.
        </p>
    </div>

    <a
        href="{{ route('evaluation-factors.criteria', $evaluation_factor) }}"
        class="btn"
    >
        ← Back
    </a>

</div>


<div class="card">

    <div class="card-header">
        <h2>Criterion Information</h2>
    </div>


    <div class="card-body">

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
            action="{{ route('evaluation-factors.criteria.store', $evaluation_factor) }}"
        >

            @csrf


            <div class="form-group">

                <label for="name">
                    Criterion Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Example: Bachelor's Degree"
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
                    rows="4"
                    placeholder="Describe this criterion..."
                >{{ old('description') }}</textarea>

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
                    value="{{ old('points', 0) }}"
                    min="0"
                    step="0.01"
                    required
                >

                <small>
                    Enter the points awarded when a candidate meets this criterion.
                </small>

            </div>


            <div class="form-actions">

                <a
                    href="{{ route('evaluation-factors.criteria', $evaluation_factor) }}"
                    class="btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Criterion
                </button>

            </div>

        </form>

    </div>

</div>

@endsection