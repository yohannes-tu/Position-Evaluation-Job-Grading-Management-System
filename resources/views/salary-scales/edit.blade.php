@extends('layouts.app')

@section('title', 'Edit Salary Scale')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Salary Scale</h1>
        <p>Update the salary scale information.</p>
    </div>

    <a href="{{ route('salary-scales.index') }}" class="btn btn-secondary">
        ← Back to Salary Scales
    </a>
</div>

<div class="form-container">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('salary-scales.update', $salaryScale) }}"
    >
        @csrf
        @method('PUT')

        {{-- Grade --}}
        <div class="form-group">
            <label for="grade_id">
                Grade <span class="required">*</span>
            </label>

            <select
                name="grade_id"
                id="grade_id"
                class="form-control"
                required
            >
                <option value="">Select Grade</option>

                @foreach ($grades as $grade)
                    <option
                        value="{{ $grade->id }}"
                        {{ old('grade_id', $salaryScale->grade_id) == $grade->id ? 'selected' : '' }}
                    >
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>

            @error('grade_id')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>


        {{-- Minimum Salary --}}
        <div class="form-row">

            <div class="form-group">
                <label for="minimum_salary">
                    Minimum Salary <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="minimum_salary"
                    id="minimum_salary"
                    class="form-control"
                    step="0.01"
                    min="0"
                    value="{{ old('minimum_salary', $salaryScale->minimum_salary) }}"
                    required
                >

                @error('minimum_salary')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>


            {{-- Midpoint Salary --}}
            <div class="form-group">
                <label for="midpoint_salary">
                    Midpoint Salary <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="midpoint_salary"
                    id="midpoint_salary"
                    class="form-control"
                    step="0.01"
                    min="0"
                    value="{{ old('midpoint_salary', $salaryScale->midpoint_salary) }}"
                    required
                >

                @error('midpoint_salary')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>


            {{-- Maximum Salary --}}
            <div class="form-group">
                <label for="maximum_salary">
                    Maximum Salary <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="maximum_salary"
                    id="maximum_salary"
                    class="form-control"
                    step="0.01"
                    min="0"
                    value="{{ old('maximum_salary', $salaryScale->maximum_salary) }}"
                    required
                >

                @error('maximum_salary')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

        </div>


        {{-- Currency and Effective Date --}}
        <div class="form-row">

            <div class="form-group">
                <label for="currency">
                    Currency <span class="required">*</span>
                </label>

                <select
                    name="currency"
                    id="currency"
                    class="form-control"
                    required
                >
                    <option value="ETB"
                        {{ old('currency', $salaryScale->currency) == 'ETB' ? 'selected' : '' }}>
                        ETB - Ethiopian Birr
                    </option>

                    <option value="USD"
                        {{ old('currency', $salaryScale->currency) == 'USD' ? 'selected' : '' }}>
                        USD - US Dollar
                    </option>

                    <option value="EUR"
                        {{ old('currency', $salaryScale->currency) == 'EUR' ? 'selected' : '' }}>
                        EUR - Euro
                    </option>
                </select>

                @error('currency')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>


            <div class="form-group">
                <label for="effective_date">
                    Effective Date <span class="required">*</span>
                </label>

                <input
                    type="date"
                    name="effective_date"
                    id="effective_date"
                    class="form-control"
                    value="{{ old('effective_date', optional($salaryScale->effective_date)->format('Y-m-d')) }}"
                    required
                >

                @error('effective_date')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

        </div>


        {{-- Status --}}
        <div class="form-group">
            <label for="status">
                Status <span class="required">*</span>
            </label>

            <select
                name="status"
                id="status"
                class="form-control"
                required
            >
                <option value="active"
                    {{ old('status', $salaryScale->status) == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $salaryScale->status) == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            @error('status')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>


        {{-- Notes --}}
        <div class="form-group">
            <label for="notes">
                Notes
            </label>

            <textarea
                name="notes"
                id="notes"
                class="form-control"
                rows="5"
                maxlength="1000"
                placeholder="Add any notes about this salary scale..."
            >{{ old('notes', $salaryScale->notes) }}</textarea>

            @error('notes')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>


        {{-- Form Actions --}}
        <div class="form-actions">

            <a
                href="{{ route('salary-scales.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Salary Scale
            </button>

        </div>

    </form>

</div>


{{-- Page-specific styling --}}
@push('styles')
<style>

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        color: #17365d;
    }

    .page-header p {
        margin-top: 5px;
        color: #64748b;
    }

    .form-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 30px;
        max-width: 1000px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .form-row + .form-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #334155;
    }

    .required {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        color: #1e293b;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    textarea.form-control {
        resize: vertical;
    }

    .field-error {
        display: block;
        margin-top: 5px;
        color: #dc2626;
        font-size: 13px;
    }

    .alert {
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert ul {
        margin: 8px 0 0 20px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        margin-top: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary {
        background: #173f6b;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #102f52;
    }

    .btn-secondary {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #334155;
    }

    .btn-secondary:hover {
        background: #f8fafc;
    }

    @media (max-width: 768px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .form-container {
            padding: 20px;
        }

        .form-row,
        .form-row + .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }
    }

</style>
@endpush

@endsection