@extends('layouts.public')

@section('title', $position->title)

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
            {{ $position->title }}
        </h1>

        <p>
            Position Code:
            <strong>{{ $position->code }}</strong>
        </p>

    </div>

</div>


<div class="card">

    <div class="card-header">
        <h2>Position Information</h2>
    </div>

    <div class="details-list">

        <div>
            <span>Position Title</span>
            <strong>{{ $position->title }}</strong>
        </div>

        <div>
            <span>Position Code</span>
            <strong>{{ $position->code }}</strong>
        </div>

        <div>
            <span>Department</span>
            <strong>
                {{ $position->department->name ?? 'N/A' }}
            </strong>
        </div>

        @if($position->approvedGrade)

            <div>
                <span>Grade</span>
                <strong>
                    {{ $position->approvedGrade->name }}
                </strong>
            </div>

        @endif

    </div>

</div>


@if($position->description)

    <div class="card">

        <div class="card-header">
            <h2>Position Summary</h2>
        </div>

        <div class="prose-content">

            {!! nl2br(e($position->description)) !!}

        </div>

    </div>

@endif


@if($position->responsibilities)

    <div class="card">

        <div class="card-header">
            <h2>Responsibilities</h2>
        </div>

        <div class="prose-content">

            {!! nl2br(e($position->responsibilities)) !!}

        </div>

    </div>

@endif


@if($position->education || $position->experience)

    <div class="card">

        <div class="card-header">
            <h2>Qualifications</h2>
        </div>

        <div class="details-list">

            @if($position->education)

                <div>
                    <span>Education</span>
                    <strong>
                        {{ $position->education }}
                    </strong>
                </div>

            @endif

            @if($position->experience)

                <div>
                    <span>Experience</span>
                    <strong>
                        {{ $position->experience }}
                    </strong>
                </div>

            @endif

        </div>

    </div>

@endif

@endsection