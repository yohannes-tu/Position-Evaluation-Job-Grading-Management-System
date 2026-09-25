@extends('layouts.app')

@section('title', 'Add Vacancy')

@section('content')

<div class="page-header">

    <div>
        <h1>Add Vacancy</h1>
        <p>Create a new recruitment vacancy.</p>
    </div>

    <a
        href="{{ route('vacancies.index') }}"
        class="btn btn-secondary"
    >
        ← Back to Vacancies
    </a>

</div>


@if($errors->any())

    <div class="alert alert-danger">

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


<div class="form-card">

    <form
        method="POST"
        action="{{ route('vacancies.store') }}"
    >

        @csrf


        {{-- Position --}}

        <div class="form-section">

            <h3>Position Information</h3>

            <div class="form-grid">

                <div class="form-group full">

                    <label for="position_id">
                        Position <span>*</span>
                    </label>

                    <select
                        name="position_id"
                        id="position_id"
                        required
                    >

                        <option value="">
                            Select Position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(old('position_id') == $position->id)
                            >

                                {{ $position->title }}
                                — {{ $position->code }}

                                @if($position->department)
                                    ({{ $position->department->name ?? 'No Department' }})
                                    Grade {{ $position->approvedGrade->name ?? '-' }}
                                    @endif

                            </option>

                        @endforeach

                    </select>

                    <small>
                        Only active positions are available for recruitment.
                    </small>

                </div>

            </div>

        </div>


        {{-- Vacancy Information --}}

        <div class="form-section">

            <h3>Vacancy Information</h3>

            <div class="form-grid">

                <div class="form-group">

                    <label for="vacancy_code">
                        Vacancy Code <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="vacancy_code"
                        name="vacancy_code"
                        value="{{ old('vacancy_code') }}"
                        placeholder="Example: VAC-2026-001"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="number_of_openings">
                        Number of Openings <span>*</span>
                    </label>

                    <input
                        type="number"
                        id="number_of_openings"
                        name="number_of_openings"
                        value="{{ old('number_of_openings', 1) }}"
                        min="1"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="employment_type">
                        Employment Type
                    </label>

                    <select
                        name="employment_type"
                        id="employment_type"
                    >

                        <option value="">
                            Select employment type
                        </option>

                        <option value="Permanent"
                            @selected(old('employment_type') === 'Permanent')>
                            Permanent
                        </option>

                        <option value="Contract"
                            @selected(old('employment_type') === 'Contract')>
                            Contract
                        </option>

                        <option value="Temporary"
                            @selected(old('employment_type') === 'Temporary')>
                            Temporary
                        </option>

                        <option value="Part-time"
                            @selected(old('employment_type') === 'Part-time')>
                            Part-time
                        </option>

                        <option value="Internship"
                            @selected(old('employment_type') === 'Internship')>
                            Internship
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="location">
                        Location
                    </label>

                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="{{ old('location') }}"
                        placeholder="Example: Addis Ababa"
                    >

                </div>


                <div class="form-group">

                    <label for="posting_date">
                        Posting Date
                    </label>

                    <input
                        type="date"
                        id="posting_date"
                        name="posting_date"
                        value="{{ old('posting_date') }}"
                    >

                </div>


                <div class="form-group">

                    <label for="closing_date">
                        Closing Date
                    </label>

                    <input
                        type="date"
                        id="closing_date"
                        name="closing_date"
                        value="{{ old('closing_date') }}"
                    >

                </div>

            </div>

        </div>


        {{-- Description --}}

        <div class="form-section">

            <h3>Vacancy Description</h3>

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="7"
                    placeholder="Describe the vacancy..."
                >{{ old('description') }}</textarea>

            </div>


            <div class="form-group">

                <label for="application_instructions">
                    Application Instructions
                </label>

                <textarea
                    id="application_instructions"
                    name="application_instructions"
                    rows="6"
                    placeholder="Explain how applicants should apply..."
                >{{ old('application_instructions') }}</textarea>

            </div>

        </div>


        {{-- Required Documents --}}

        <div class="form-section">

    <h3>Required Documents</h3>

    <div class="form-group">

        <label for="required_documents">
            Required Documents
        </label>

        <textarea
            id="required_documents"
            name="required_documents"
            rows="6"
            placeholder="Enter one document per line&#10;Example:&#10;CV / Resume&#10;Educational Certificate&#10;Work Experience Letter&#10;National ID"
        >{{ old('required_documents') }}</textarea>

        <small>
            Enter one required document per line.
        </small>

    </div>

</div>


        {{-- Salary --}}

       <div class="form-section">

    <h3>Salary Visibility</h3>

    <label class="checkbox-label">

        <input
            type="checkbox"
            name="show_salary"
            value="1"
            @checked(old('show_salary'))
        >

        <span>
            Show salary information on the public vacancy.
        </span>

    </label>

    <small>
        Salary information remains hidden from public users unless this option is enabled.
    </small>

</div>


        {{-- Actions --}}

       <div class="form-actions">

    <a
        href="{{ route('vacancies.index') }}"
        class="btn btn-secondary"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Create Vacancy
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
    color: #17365d;
    font-size: 28px;
}

.page-header p {
    margin: 5px 0 0;
    color: #64748b;
}


.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 17px;
    border-radius: 6px;
    border: 1px solid transparent;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.btn-primary {
    background: #173f6b;
    color: white;
}

.btn-secondary {
    background: white;
    color: #334155;
    border-color: #cbd5e1;
}


.alert {
    padding: 15px 18px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert ul {
    margin: 8px 0 0;
}


.form-card {
    max-width: 1000px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 30px;
}

.form-section {
    padding-bottom: 28px;
    margin-bottom: 28px;
    border-bottom: 1px solid #e2e8f0;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    margin: 0 0 20px;
    font-size: 18px;
    color: #17365d;
}

.section-help {
    color: #64748b;
    font-size: 14px;
    margin-top: -10px;
    margin-bottom: 15px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}

.form-group label span {
    color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
    font-family: inherit;
    background: white;
}

.form-group textarea {
    resize: vertical;
}

.form-group small,
.form-section > small {
    color: #64748b;
    font-size: 12px;
}


.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #334155;
    font-size: 14px;
    margin-bottom: 8px;
}

.checkbox-label input {
    width: 17px;
    height: 17px;
}


.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}


@media(max-width: 700px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .form-card {
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
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