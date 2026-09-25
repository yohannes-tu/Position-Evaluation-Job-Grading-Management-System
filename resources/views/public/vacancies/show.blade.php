@extends('layouts.public')

@section('title', $vacancy->position->title . ' - Vacancy')

@section('content')

<div class="page-header">

    <div>

        <a
            href="{{ route('public.vacancies.index') }}"
            class="back-link"
        >
            ← Back to Vacancies
        </a>

        <h1>
            {{ $vacancy->position->title }}
        </h1>

        <p>
            Vacancy Code:
            <strong>{{ $vacancy->vacancy_code }}</strong>
        </p>

    </div>

    <span class="status-badge status-success">
        Published
    </span>

</div>


<div class="details-grid">

    <div>

        <div class="card">

            <div class="card-header">
                <h2>Position Information</h2>
            </div>

            <div class="details-list">

                <div>
                    <span>Position</span>
                    <strong>
                        {{ $vacancy->position->title }}
                    </strong>
                </div>

                <div>
                    <span>Position Code</span>
                    <strong>
                        {{ $vacancy->position->code }}
                    </strong>
                </div>

                <div>
                    <span>Department</span>
                    <strong>
                        {{ $vacancy->position->department->name ?? 'N/A' }}
                    </strong>
                </div>

                @if($vacancy->position->approvedGrade)
                    <div>
                        <span>Grade</span>
                        <strong>
                            {{ $vacancy->position->approvedGrade->name }}
                        </strong>
                    </div>
                @endif

                <div>
                    <span>Location</span>
                    <strong>
                        {{ $vacancy->location ?: 'Not specified' }}
                    </strong>
                </div>

                <div>
                    <span>Employment Type</span>
                    <strong>
                        {{ ucwords(str_replace('_', ' ', $vacancy->employment_type)) }}
                    </strong>
                </div>

                <div>
                    <span>Number of Openings</span>
                    <strong>
                        {{ $vacancy->number_of_openings }}
                    </strong>
                </div>

            </div>

        </div>


        <div class="card">

            <div class="card-header">
                <h2>Job Description</h2>
            </div>

            <div class="prose-content">

                {!! nl2br(e($vacancy->description)) !!}

            </div>

        </div>


        @if($vacancy->position->description)

            <div class="card">

                <div class="card-header">
                    <h2>Position Summary</h2>
                </div>

                <div class="prose-content">

                    {!! nl2br(e($vacancy->position->description)) !!}

                </div>

            </div>

        @endif


        @if($vacancy->position->responsibilities)

            <div class="card">

                <div class="card-header">
                    <h2>Responsibilities</h2>
                </div>

                <div class="prose-content">

                    {!! nl2br(e($vacancy->position->responsibilities)) !!}

                </div>

            </div>

        @endif


        @if(
            $vacancy->position->education ||
            $vacancy->position->experience
        )

            <div class="card">

                <div class="card-header">
                    <h2>Requirements</h2>
                </div>

                <div class="details-list">

                    @if($vacancy->position->education)
                        <div>
                            <span>Education</span>
                            <strong>
                                {{ $vacancy->position->education }}
                            </strong>
                        </div>
                    @endif

                    @if($vacancy->position->experience)
                        <div>
                            <span>Experience</span>
                            <strong>
                                {{ $vacancy->position->experience }}
                            </strong>
                        </div>
                    @endif

                </div>

            </div>

        @endif

    </div>


    <aside>

        <div class="card vacancy-apply-card">

            <h2>Application</h2>

            <div class="deadline">

                <span>Application Deadline</span>

                <strong>
                    {{ optional($vacancy->closing_date)->format('F d, Y') }}
                </strong>

            </div>


            @if($vacancy->posting_date)

                <div class="deadline">

                    <span>Posted On</span>

                    <strong>
                        {{ $vacancy->posting_date->format('F d, Y') }}
                    </strong>

                </div>

            @endif


            @if($vacancy->show_salary)

                @php
                    $salaryScale = $vacancy->position->approvedGrade
                        ? \App\Models\SalaryScale::where(
                            'grade_id',
                            $vacancy->position->approvedGrade->id
                        )
                        ->where('status', 'active')
                        ->whereDate(
                            'effective_date',
                            '<=',
                            now()->toDateString()
                        )
                        ->latest('effective_date')
                        ->first()
                        : null;
                @endphp

                @if($salaryScale)

                    <div class="salary-box">

                        <span>Salary Range</span>

                        <strong>
                            {{ number_format($salaryScale->minimum_salary, 2) }}
                            –
                            {{ number_format($salaryScale->maximum_salary, 2) }}
                            {{ $salaryScale->currency }}
                        </strong>

                    </div>

                @endif

            @endif


            <a
                href="{{ route('public.applications.create', $vacancy) }}"
                class="btn btn-primary btn-block"
            >
                Apply for this Position
            </a>

        </div>


        @if($vacancy->required_documents)

            <div class="card">

                <div class="card-header">
                    <h2>Required Documents</h2>
                </div>

                <ul class="document-list">

                    @foreach($vacancy->required_documents as $document)

                        <li>
                            ✓ {{ $document }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        @if($vacancy->application_instructions)

            <div class="card">

                <div class="card-header">
                    <h2>Application Instructions</h2>
                </div>

                <div class="prose-content">

                    {!! nl2br(e($vacancy->application_instructions)) !!}

                </div>

            </div>

        @endif

    </aside>

</div>


<div
    id="application"
    class="card application-placeholder"
>

    <h2>How to Apply</h2>

    <p>
        Please review the application instructions and prepare all
        required documents before applying.
    </p>

    <p>
        The online application module will be connected to this vacancy
        portal in the next recruitment phase.
    </p>

</div>

@endsection