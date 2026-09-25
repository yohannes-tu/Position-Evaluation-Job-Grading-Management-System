@extends('layouts.app')

@section('content')

<div class="page-container">

    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h1>Edit Position</h1>

            <p>
                Update information for
                <strong>{{ $position->title }}</strong>.
            </p>
        </div>

        <a
            href="{{ route('positions.show', $position) }}"
            class="btn-secondary">
            ← Back to Position
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert-error">

            <strong>
                Please correct the following errors:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Edit Form --}}
    <div class="card">

        <div class="card-header">

            <h2>Position Information</h2>

        </div>


        <form
            method="POST"
            action="{{ route('positions.update', $position) }}"
            class="form">

            @csrf

            @method('PUT')


            {{-- Position Title --}}
            <div class="form-group">

                <label for="title">
                    Position Title
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $position->title) }}"
                    placeholder="e.g. Software Developer"
                    maxlength="255"
                    class="@error('title') input-error @enderror"
                    required
                >

                @error('title')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Position Code --}}
            <div class="form-group">

                <label for="code">
                    Position Code
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code', $position->code) }}"
                    placeholder="e.g. IT-SD-001"
                    maxlength="50"
                    class="@error('code') input-error @enderror"
                    oninput="this.value = this.value.toUpperCase()"
                    required
                >

                <small class="form-help">
                    Use letters, numbers, hyphens, or underscores.
                </small>

                @error('code')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Department --}}
            <div class="form-group">

                <label for="department_id">
                    Department
                    <span class="required">*</span>
                </label>

                <select
                    id="department_id"
                    name="department_id"
                    class="@error('department_id') input-error @enderror"
                    required>

                    <option value="">
                        -- Select Department --
                    </option>

                    @foreach($departments as $department)

                        <option
                            value="{{ $department->id }}"
                            {{ old(
                                'department_id',
                                $position->department_id
                            ) == $department->id ? 'selected' : '' }}>

                            {{ $department->name }}
                            ({{ $department->code }})

                        </option>

                    @endforeach

                </select>

                @error('department_id')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Employment Type --}}
            <div class="form-group">

                <label for="employment_type">
                    Employment Type
                    <span class="required">*</span>
                </label>

                <select
                    id="employment_type"
                    name="employment_type"
                    class="@error('employment_type') input-error @enderror"
                    required>

                    <option value="">
                        -- Select Employment Type --
                    </option>

                    <option
                        value="full_time"
                        {{ old(
                            'employment_type',
                            $position->employment_type
                        ) === 'full_time' ? 'selected' : '' }}>
                        Full Time
                    </option>

                    <option
                        value="part_time"
                        {{ old(
                            'employment_type',
                            $position->employment_type
                        ) === 'part_time' ? 'selected' : '' }}>
                        Part Time
                    </option>

                    <option
                        value="contract"
                        {{ old(
                            'employment_type',
                            $position->employment_type
                        ) === 'contract' ? 'selected' : '' }}>
                        Contract
                    </option>

                </select>

                @error('employment_type')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Education --}}
            <div class="form-group">

                <label for="education">
                    Minimum Education
                </label>

                <input
                    type="text"
                    id="education"
                    name="education"
                    value="{{ old('education', $position->education) }}"
                    placeholder="e.g. BSc in Computer Science or related field"
                    maxlength="255"
                    class="@error('education') input-error @enderror"
                >

                @error('education')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Experience --}}
            <div class="form-group">

                <label for="experience">
                    Minimum Experience
                </label>

                <input
                    type="text"
                    id="experience"
                    name="experience"
                    value="{{ old('experience', $position->experience) }}"
                    placeholder="e.g. 2 years"
                    maxlength="255"
                    class="@error('experience') input-error @enderror"
                >

                @error('experience')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Description --}}
            <div class="form-group">

                <label for="description">
                    Position Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    maxlength="2000"
                    placeholder="Describe the purpose of this position..."
                    class="@error('description') input-error @enderror"
                >{{ old('description', $position->description) }}</textarea>

                <small class="form-help">
                    Maximum 2000 characters.
                </small>

                @error('description')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Responsibilities --}}
            <div class="form-group">

                <label for="responsibilities">
                    Main Responsibilities
                </label>

                <textarea
                    id="responsibilities"
                    name="responsibilities"
                    rows="7"
                    maxlength="5000"
                    placeholder="List the main responsibilities..."
                    class="@error('responsibilities') input-error @enderror"
                >{{ old(
                    'responsibilities',
                    $position->responsibilities
                ) }}</textarea>

                <small class="form-help">
                    Maximum 5000 characters.
                </small>

                @error('responsibilities')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Status --}}
            <div class="form-group">

                <label for="is_active">
                    Status
                    <span class="required">*</span>
                </label>

                <select
                    id="is_active"
                    name="is_active"
                    required>

                    <option
                        value="1"
                        {{ old(
                            'is_active',
                            $position->is_active
                        ) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option
                        value="0"
                        {{ old(
                            'is_active',
                            $position->is_active
                        ) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Actions --}}
            <div class="form-actions">

                <a
                    href="{{ route('positions.show', $position) }}"
                    class="btn-secondary">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-primary">
                    Update Position
                </button>

            </div>

        </form>

    </div>

</div>

@endsection