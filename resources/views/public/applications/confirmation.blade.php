@extends('layouts.public')

@section(
    'title',
    'Application Submitted'
)

@section('content')

<div class="page-header">

    <div>

        <h1>
            Application Submitted Successfully
        </h1>

        <p>
            Thank you for applying.
            Your application has been received.
        </p>

    </div>

</div>


<div class="card application-confirmation">

    <div class="confirmation-icon">
        ✓
    </div>


    <h2>
        Your application has been submitted
    </h2>


    <p>
        Please keep your application number for future reference.
    </p>


    <div class="application-number-box">

        <span>
            Application Number
        </span>

        <strong>
            {{ $application->application_number }}
        </strong>

    </div>


    <div class="confirmation-details">

        <div>
            <span>Applicant</span>

            <strong>
                {{ $application->applicant->full_name }}
            </strong>
        </div>


        <div>
            <span>Position</span>

            <strong>
                {{ $application->vacancy->position->title }}
            </strong>
        </div>


        <div>
            <span>Vacancy Code</span>

            <strong>
                {{ $application->vacancy->vacancy_code }}
            </strong>
        </div>


        <div>
            <span>Submitted</span>

            <strong>
                {{ $application->submitted_at?->format('d M Y, h:i A') }}
            </strong>
        </div>


        <div>
            <span>Status</span>

            <strong>
                Submitted
            </strong>
        </div>

    </div>


    <div class="confirmation-message">

        <h3>What's next?</h3>

        <p>
            Your application will be reviewed by the recruitment team.
            If you are shortlisted, the organization may contact you
            using the information provided in your application.
        </p>

    </div>


    <div class="form-actions">

        <a
            href="{{ route('public.vacancies.index') }}"
            class="btn btn-primary"
        >
            Browse More Vacancies
        </a>

        <button
            type="button"
            class="btn btn-secondary"
            onclick="window.print()"
        >
            Print Confirmation
        </button>

    </div>

</div>

@endsection