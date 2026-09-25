@extends('layouts.app')

@section('title', 'Add Evaluation Factor')

@section('content')

<div class="page-header">

    <div>
        <h1>Add Evaluation Factor</h1>

        <p>
            Create a factor that will be used to evaluate positions.
        </p>
    </div>

    <a
        href="{{ route('evaluation-factors.index') }}"
        class="btn"
    >
        ← Back
    </a>

</div>


<div class="card">

    <div class="card-header">
        <h2>Factor Information</h2>
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
            action="{{ route('evaluation-factors.store') }}"
        >

            @csrf


            {{-- Factor Name --}}

            <div class="form-group">

                <label for="name">
                    Factor Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Example: Education"
                    required
                >

                <small>
                    Enter the name of the evaluation factor.
                </small>

            </div>


            {{-- Description --}}

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Describe what this factor measures..."
                >{{ old('description') }}</textarea>

                <small>
                    Explain how this factor will be used when evaluating positions.
                </small>

            </div>

            <div>
    <label for="weight">Weight (%) *</label>

    <input
        type="number"
        name="weight"
        id="weight"
        value="{{ old('weight') }}"
        min="0"
        max="100"
        step="0.01"
        required
    >

    @error('weight')
        <p>{{ $message }}</p>
    @enderror

    <small>
        Enter the percentage importance of this factor.
    </small>
</div>

<div>
    <label for="max_score">Maximum Score *</label>

    <input
        type="number"
        name="max_score"
        id="max_score"
        value="{{ old('max_score', 100) }}"
        min="1"
        step="0.01"
        required
    >

    @error('max_score')
        <p>{{ $message }}</p>
    @enderror

    <small>
        Enter the maximum score that can be awarded for this factor.
    </small>
</div>


            {{-- Buttons --}}

            <div class="form-actions">

                <a
                    href="{{ route('evaluation-factors.index') }}"
                    class="btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Factor
                </button>

            </div>

        </form>

    </div>

</div>

@endsection