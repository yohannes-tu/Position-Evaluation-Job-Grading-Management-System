@extends('layouts.app')

@section('title', 'Add Department')

@section('page-title', 'Add Department')

@section('content')

    <div class="page-header">

        <div>
            <h2>Add Department</h2>
            <p>Create a new organizational department.</p>
        </div>

        <a href="{{ route('departments.index') }}" class="btn-secondary">
            ← Back to Departments
        </a>

    </div>


    <div class="form-card">

        <div class="form-card-header">
            <h3>Department Information</h3>
            <p>Enter the information for the new department.</p>
        </div>


        <form
            method="POST"
            action="{{ route('departments.store') }}"
            class="department-form">

            @csrf


            {{-- Department Name --}}

            <div class="form-group">

                <label for="name">
                    Department Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Information Technology"
                    class="@error('name') input-error @enderror"
                    required
                >

                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror

            </div>


            {{-- Department Code --}}

            <div class="form-group">

                <label for="code">
                    Department Code
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="e.g. IT"
                    maxlength="50"
                    class="@error('code') input-error @enderror"
                    oninput="this.value = this.value.toUpperCase()"
                    required
                >

                <small class="form-help">
                    Use a short unique code for the department.
                </small>

                @error('code')
                    <span class="error-message">{{ $message }}</span>
                @enderror

            </div>


            {{-- Description --}}

            <div class="form-group">

                <label for="description">
                    Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    maxlength="1000"
                    placeholder="Describe the purpose or responsibilities of this department..."
                    class="@error('description') input-error @enderror"
                >{{ old('description') }}</textarea>

                @error('description')
                    <span class="error-message">{{ $message }}</span>
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
                    class="@error('is_active') input-error @enderror"
                    required
                >

                    <option value="1"
                        {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('is_active') === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('is_active')
                    <span class="error-message">{{ $message }}</span>
                @enderror

            </div>


            {{-- Form actions --}}

            <div class="form-actions">

                <a
                    href="{{ route('departments.index') }}"
                    class="btn-secondary">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-primary">
                    Save Department
                </button>

            </div>

        </form>

    </div>

@endsection