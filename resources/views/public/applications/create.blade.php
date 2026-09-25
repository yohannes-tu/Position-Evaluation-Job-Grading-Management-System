@extends('layouts.public')

@section('title', 'Apply - ' . $vacancy->position->title)

@section('content')

<div class="page-header">

    <div>

        <a
            href="{{ route('public.vacancies.show', $vacancy) }}"
            class="back-link"
        >
            ← Back to Vacancy
        </a>

        <h1>
            Apply for {{ $vacancy->position->title }}
        </h1>

        <p>
            Vacancy Code:
            <strong>{{ $vacancy->vacancy_code }}</strong>
        </p>

    </div>

</div>


<div class="card">

    <div class="card-header">
        <h2>Application Information</h2>

        <p>
            Please provide accurate information.
            Fields marked with * are required.
        </p>
    </div>


    <form
       method="POST"
       action="{{ route('public.applications.store', $vacancy) }}"
       enctype="multipart/form-data"
    >

        @csrf


        <div class="form-section">

            <h3>Personal Information</h3>


            <div class="form-grid">

                <div class="form-group">

                    <label for="first_name">
                        First Name *
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="middle_name">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        value="{{ old('middle_name') }}"
                    >

                </div>


                <div class="form-group">

                    <label for="last_name">
                        Last Name *
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email *
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="phone">
                        Phone Number *
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+251..."
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="gender">
                        Gender
                    </label>

                    <select
                        id="gender"
                        name="gender"
                    >

                        <option value="">
                            Select
                        </option>

                        <option
                            value="male"
                            @selected(old('gender') === 'male')
                        >
                            Male
                        </option>

                        <option
                            value="female"
                            @selected(old('gender') === 'female')
                        >
                            Female
                        </option>

                        <option
                            value="other"
                            @selected(old('gender') === 'other')
                        >
                            Other
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="date_of_birth">
                        Date of Birth
                    </label>

                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                    >

                </div>


                <div class="form-group">

                    <label for="national_id">
                        National ID
                    </label>

                    <input
                        type="text"
                        id="national_id"
                        name="national_id"
                        value="{{ old('national_id') }}"
                    >

                </div>

            </div>

        </div>


        <div class="form-section">

            <h3>Address</h3>

            <div class="form-grid">

                <div class="form-group">

                    <label for="region">
                        Region
                    </label>

                    <input
                        type="text"
                        id="region"
                        name="region"
                        value="{{ old('region') }}"
                        placeholder="e.g. Addis Ababa"
                    >

                </div>


                <div class="form-group">

                    <label for="city">
                        City
                    </label>

                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city') }}"
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="address">
                    Address
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="3"
                >{{ old('address') }}</textarea>

            </div>

        </div>


        <div class="form-section">

            <h3>Cover Letter</h3>

            <div class="form-group">

                <label for="cover_letter">
                    Cover Letter
                </label>

                <textarea
                    id="cover_letter"
                    name="cover_letter"
                    rows="8"
                    placeholder="Explain why you are suitable for this position..."
                >{{ old('cover_letter') }}</textarea>

            </div>

        </div>


        <div class="form-section">

            <h3>Required Documents</h3>

            @if($vacancy->required_documents)

                <div class="required-document-list">

                    @foreach($vacancy->required_documents as $index => $document)

    <div class="required-document-item">

        <label for="document_{{ $index }}">
            {{ $document }}
        </label>

        <input
            type="file"
            id="document_{{ $index }}"
            name="documents[{{ $index }}]"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
        >

        <small>
            Maximum file size: 5 MB.
        </small>

    </div>

@endforeach

                </div>

            @else

                <p>
                    No specific documents have been configured
                    for this vacancy.
                </p>

            @endif

        </div>


        <div class="form-section">

            <label class="checkbox-label">

                <input
                    type="checkbox"
                    name="declaration"
                    value="1"
                    required
                >

                <span>
                    I declare that the information provided in this
                    application is accurate and complete.
                </span>

            </label>

        </div>


        <div class="form-actions">

            <a
                href="{{ route('public.vacancies.show', $vacancy) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Submit Application
            </button>

        </div>

    </form>

</div>

@endsection