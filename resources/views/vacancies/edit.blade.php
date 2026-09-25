@extends('layouts.app')

@section('title', 'Edit Vacancy')

@section('content')

<div class="page-header">

    <div>
        <h1>Edit Vacancy</h1>
        <p>
            Update vacancy information before publication.
        </p>
    </div>

    <a
        href="{{ route('vacancies.show', $vacancy) }}"
        class="btn btn-secondary"
    >
        ← Back to Vacancy
    </a>

</div>


@if($errors->any())

    <div class="alert alert-danger">

        <strong>Please correct the following errors:</strong>

        <ul style="margin-top:10px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

@endif


<div class="card">

    <div class="card-header">
        <h3>Vacancy Information</h3>
    </div>

    <div style="padding:24px;">

        <form
            method="POST"
            action="{{ route('vacancies.update', $vacancy) }}"
        >

            @csrf
            @method('PUT')


            {{-- Position --}}
            <div class="form-group">

                <label for="position_id">
                    Position <span class="required">*</span>
                </label>

                <select
                    name="position_id"
                    id="position_id"
                    class="form-control"
                    required
                >

                    <option value="">
                        Select Position
                    </option>

                    @foreach($positions as $position)

                        <option
                            value="{{ $position->id }}"
                            @selected(
                                old(
                                    'position_id',
                                    $vacancy->position_id
                                ) == $position->id
                            )
                        >
                            {{ $position->title }}
                            ({{ $position->code }})
                            -
                            {{ $position->department->name ?? 'No Department' }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Vacancy Code --}}
            <div class="form-group">

                <label for="vacancy_code">
                    Vacancy Code <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="vacancy_code"
                    id="vacancy_code"
                    class="form-control"
                    value="{{ old(
                        'vacancy_code',
                        $vacancy->vacancy_code
                    ) }}"
                    required
                >

            </div>


            {{-- Openings --}}
            <div class="form-group">

                <label for="number_of_openings">
                    Number of Openings
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="number_of_openings"
                    id="number_of_openings"
                    class="form-control"
                    min="1"
                    value="{{ old(
                        'number_of_openings',
                        $vacancy->number_of_openings
                    ) }}"
                    required
                >

            </div>


            {{-- Dates --}}
            <div
                style="
                    display:grid;
                    grid-template-columns:
                        repeat(auto-fit, minmax(220px, 1fr));
                    gap:20px;
                "
            >

                <div class="form-group">

                    <label for="posting_date">
                        Posting Date
                        <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="posting_date"
                        id="posting_date"
                        class="form-control"
                        value="{{ old(
                            'posting_date',
                            \Carbon\Carbon::parse(
                                $vacancy->posting_date
                            )->format('Y-m-d')
                        ) }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="closing_date">
                        Closing Date
                        <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="closing_date"
                        id="closing_date"
                        class="form-control"
                        value="{{ old(
                            'closing_date',
                            \Carbon\Carbon::parse(
                                $vacancy->closing_date
                            )->format('Y-m-d')
                        ) }}"
                        required
                    >

                </div>

            </div>


            {{-- Employment Type --}}
            <div class="form-group">

                <label for="employment_type">
                    Employment Type
                    <span class="required">*</span>
                </label>

                <select
                    name="employment_type"
                    id="employment_type"
                    class="form-control"
                    required
                >

                    @foreach([
                        'Permanent',
                        'Contract',
                        'Temporary',
                        'Internship',
                        'Part-time'
                    ] as $type)

                        <option
                            value="{{ $type }}"
                            @selected(
                                old(
                                    'employment_type',
                                    $vacancy->employment_type
                                ) === $type
                            )
                        >
                            {{ $type }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Location --}}
            <div class="form-group">

                <label for="location">
                    Location <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="location"
                    id="location"
                    class="form-control"
                    value="{{ old(
                        'location',
                        $vacancy->location
                    ) }}"
                    placeholder="e.g. Addis Ababa"
                    required
                >

            </div>


            {{-- Description --}}
            <div class="form-group">

                <label for="description">
                    Vacancy Description
                    <span class="required">*</span>
                </label>

                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="8"
                    required
                >{{ old(
                    'description',
                    $vacancy->description
                ) }}</textarea>

            </div>


            {{-- Application Instructions --}}
            <div class="form-group">

                <label for="application_instructions">
                    Application Instructions
                </label>

                <textarea
                    name="application_instructions"
                    id="application_instructions"
                    class="form-control"
                    rows="6"
                >{{ old(
                    'application_instructions',
                    $vacancy->application_instructions
                ) }}</textarea>

            </div>


            {{-- Required Documents --}}
            <div class="form-group">

                <label for="required_documents">
                    Required Documents
                </label>

                @php
    $requiredDocuments = $vacancy->required_documents;

    if (is_array($requiredDocuments)) {
        $requiredDocuments = implode(PHP_EOL, $requiredDocuments);
    }

    $requiredDocuments = $requiredDocuments ?? '';
@endphp

<textarea
    name="required_documents"
    id="required_documents"
    class="form-control"
    rows="6"
    placeholder="List required documents..."
>{{ old('required_documents', $requiredDocuments) }}</textarea>

            </div>


            {{-- Salary --}}
            <div class="form-group">

                <label
                    style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        cursor:pointer;
                    "
                >

                    <input
                        type="checkbox"
                        name="show_salary"
                        value="1"
                        @checked(
                            old(
                                'show_salary',
                                $vacancy->show_salary
                            )
                        )
                    >

                    <span>
                        Show salary information publicly
                    </span>

                </label>

            </div>


            {{-- Actions --}}
            <div
                style="
                    display:flex;
                    gap:10px;
                    justify-content:flex-end;
                    flex-wrap:wrap;
                    margin-top:30px;
                "
            >

                <a
                    href="{{ route('vacancies.show', $vacancy) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection