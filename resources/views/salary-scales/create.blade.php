@extends('layouts.app')

@section('title', 'Add Salary Scale')

@section('content')

<div class="page-header">
    <div>
        <h1>Add Salary Scale</h1>
        <p>Create a salary range for a grade.</p>
    </div>

    <a href="{{ route('salary-scales.index') }}" class="btn btn-secondary">
        ← Back to Salary Scales
    </a>
</div>

<div class="form-card">

    <form method="POST" action="{{ route('salary-scales.store') }}">
        @csrf

        {{-- Grade --}}
        <div class="form-group">
            <label for="grade_id">
                Grade <span class="required">*</span>
            </label>

            <select
                name="grade_id"
                id="grade_id"
                class="form-control @error('grade_id') is-invalid @enderror"
                required
            >
                <option value="">Select Grade</option>

                @foreach($grades as $grade)
                    <option
                        value="{{ $grade->id }}"
                        {{ old('grade_id') == $grade->id ? 'selected' : '' }}
                    >
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>

            @error('grade_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        {{-- Salary Information --}}
        <div class="form-section">
            <h2>Salary Information</h2>
            <p>Define the minimum, midpoint, and maximum salary for this grade.</p>
        </div>

        <div class="form-grid">

            {{-- Minimum Salary --}}
            <div class="form-group">
                <label for="minimum_salary">
                    Minimum Salary <span class="required">*</span>
                </label>

                <div class="input-with-prefix">
                    <span>ETB</span>

                    <input
                        type="number"
                        name="minimum_salary"
                        id="minimum_salary"
                        class="form-control @error('minimum_salary') is-invalid @enderror"
                        value="{{ old('minimum_salary') }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >
                </div>

                @error('minimum_salary')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>


            {{-- Midpoint Salary --}}
            <div class="form-group">
                <label for="midpoint_salary">
                    Midpoint Salary <span class="required">*</span>
                </label>

                <div class="input-with-prefix">
                    <span>ETB</span>

                    <input
                        type="number"
                        name="midpoint_salary"
                        id="midpoint_salary"
                        class="form-control @error('midpoint_salary') is-invalid @enderror"
                        value="{{ old('midpoint_salary') }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >
                </div>

                @error('midpoint_salary')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>


            {{-- Maximum Salary --}}
            <div class="form-group">
                <label for="maximum_salary">
                    Maximum Salary <span class="required">*</span>
                </label>

                <div class="input-with-prefix">
                    <span>ETB</span>

                    <input
                        type="number"
                        name="maximum_salary"
                        id="maximum_salary"
                        class="form-control @error('maximum_salary') is-invalid @enderror"
                        value="{{ old('maximum_salary') }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >
                </div>

                @error('maximum_salary')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

        </div>


        {{-- Currency --}}
        <div class="form-group">
            <label for="currency">
                Currency <span class="required">*</span>
            </label>

            <select
                name="currency"
                id="currency"
                class="form-control @error('currency') is-invalid @enderror"
                required
            >
                <option value="ETB" {{ old('currency', 'ETB') === 'ETB' ? 'selected' : '' }}>
                    Ethiopian Birr (ETB)
                </option>

                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>
                    US Dollar (USD)
                </option>

                <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>
                    Euro (EUR)
                </option>
            </select>

            @error('currency')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        {{-- Effective Date --}}
        <div class="form-group">
            <label for="effective_date">
                Effective Date <span class="required">*</span>
            </label>

            <input
                type="date"
                name="effective_date"
                id="effective_date"
                class="form-control @error('effective_date') is-invalid @enderror"
                value="{{ old('effective_date') }}"
                required
            >

            @error('effective_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        {{-- Status --}}
        <div class="form-group">
            <label for="status">
                Status <span class="required">*</span>
            </label>

            <select
                name="status"
                id="status"
                class="form-control @error('status') is-invalid @enderror"
                required
            >
                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            @error('status')
                <div class="error-message">{{ $message }}</div>
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
                rows="4"
                class="form-control @error('notes') is-invalid @enderror"
                placeholder="Enter any additional notes about this salary scale..."
            >{{ old('notes') }}</textarea>

            @error('notes')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        {{-- Salary Preview --}}
        <div class="salary-preview">
            <div>
                <span>Minimum</span>
                <strong id="preview-minimum">ETB 0.00</strong>
            </div>

            <div>
                <span>Midpoint</span>
                <strong id="preview-midpoint">ETB 0.00</strong>
            </div>

            <div>
                <span>Maximum</span>
                <strong id="preview-maximum">ETB 0.00</strong>
            </div>
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
                Save Salary Scale
            </button>

        </div>

    </form>

</div>


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

.form-card {
    background: white;
    border: 1px solid #dbe2ea;
    border-radius: 8px;
    padding: 30px;
    max-width: 1000px;
}

.form-section {
    margin: 25px 0 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.form-section h2 {
    margin: 0;
    font-size: 18px;
    color: #17365d;
}

.form-section p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 14px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
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
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: white;
    font-size: 14px;
    color: #1e293b;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

textarea.form-control {
    resize: vertical;
}

.is-invalid {
    border-color: #dc2626;
}

.error-message {
    margin-top: 5px;
    color: #dc2626;
    font-size: 13px;
}

.input-with-prefix {
    display: flex;
    align-items: stretch;
}

.input-with-prefix span {
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-right: none;
    border-radius: 6px 0 0 6px;
    color: #475569;
    font-weight: 600;
    font-size: 13px;
}

.input-with-prefix .form-control {
    border-radius: 0 6px 6px 0;
}

.salary-preview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 25px;
    padding: 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.salary-preview div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.salary-preview span {
    font-size: 13px;
    color: #64748b;
}

.salary-preview strong {
    color: #17365d;
    font-size: 18px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

.btn-primary {
    background: #17365d;
    color: white;
}

.btn-primary:hover {
    background: #102a49;
}

.btn-secondary {
    background: white;
    color: #334155;
    border-color: #cbd5e1;
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

    .form-grid {
        grid-template-columns: 1fr;
    }

    .salary-preview {
        grid-template-columns: 1fr;
    }

    .form-card {
        padding: 20px;
    }
}

</style>
@endpush


@push('scripts')
<script>

function updateSalaryPreview() {

    const minimum =
        parseFloat(document.getElementById('minimum_salary').value) || 0;

    const midpoint =
        parseFloat(document.getElementById('midpoint_salary').value) || 0;

    const maximum =
        parseFloat(document.getElementById('maximum_salary').value) || 0;

    document.getElementById('preview-minimum').textContent =
        'ETB ' + minimum.toLocaleString('en-US', {
            minimumFractionDigits: 2
        });

    document.getElementById('preview-midpoint').textContent =
        'ETB ' + midpoint.toLocaleString('en-US', {
            minimumFractionDigits: 2
        });

    document.getElementById('preview-maximum').textContent =
        'ETB ' + maximum.toLocaleString('en-US', {
            minimumFractionDigits: 2
        });
}

document
    .getElementById('minimum_salary')
    .addEventListener('input', updateSalaryPreview);

document
    .getElementById('midpoint_salary')
    .addEventListener('input', updateSalaryPreview);

document
    .getElementById('maximum_salary')
    .addEventListener('input', updateSalaryPreview);

</script>
@endpush

@endsection