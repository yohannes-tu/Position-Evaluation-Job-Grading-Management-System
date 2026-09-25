@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Schedule Interview
        </h1>

        <p>
            Schedule an interview for the shortlisted applicant.
        </p>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h2>
            Applicant Information
        </h2>

    </div>


    <div class="detail-grid">

        <div>

            <span class="detail-label">
                Applicant
            </span>

            <strong>
                {{ $application->applicant->full_name }}
            </strong>

        </div>


        <div>

            <span class="detail-label">
                Email
            </span>

            <strong>
                {{ $application->applicant->email }}
            </strong>

        </div>


        <div>

            <span class="detail-label">
                Position
            </span>

            <strong>
                {{ $application->vacancy->position->title }}
            </strong>

        </div>


        <div>

            <span class="detail-label">
                Vacancy
            </span>

            <strong>
                {{ $application->vacancy->vacancy_code }}
            </strong>

        </div>

    </div>

</div>


<form
    method="POST"
    action="{{ route(
        'interviews.store',
        $application
    ) }}"
>

    @csrf


    <div class="card">

        <div class="card-header">

            <h2>
                Interview Details
            </h2>

        </div>


        <div class="form-grid">

            <div class="form-group">

                <label for="scheduled_at">
                    Date & Time
                </label>

                <input
                    type="datetime-local"
                    id="scheduled_at"
                    name="scheduled_at"
                    value="{{ old('scheduled_at') }}"
                    required
                >

                @error('scheduled_at')
                    <small class="form-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="form-group">

                <label for="duration_minutes">
                    Duration
                </label>

                <select
                    id="duration_minutes"
                    name="duration_minutes"
                    required
                >

                    <option value="30">
                        30 minutes
                    </option>

                    <option
                        value="45"
                        @selected(old('duration_minutes') == 45)
                    >
                        45 minutes
                    </option>

                    <option
                        value="60"
                        @selected(
                            old(
                                'duration_minutes',
                                60
                            ) == 60
                        )
                    >
                        1 hour
                    </option>

                    <option
                        value="90"
                        @selected(old('duration_minutes') == 90)
                    >
                        1 hour 30 minutes
                    </option>

                    <option
                        value="120"
                        @selected(old('duration_minutes') == 120)
                    >
                        2 hours
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label for="interview_type">
                    Interview Type
                </label>

                <select
                    id="interview_type"
                    name="interview_type"
                    required
                >

                    <option value="in_person">
                        In Person
                    </option>

                    <option value="online">
                        Online
                    </option>

                    <option value="phone">
                        Phone
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
                    placeholder="Interview room / office"
                >

            </div>


            <div class="form-group">

                <label for="meeting_link">
                    Meeting Link
                </label>

                <input
                    type="url"
                    id="meeting_link"
                    name="meeting_link"
                    value="{{ old('meeting_link') }}"
                    placeholder="https://..."
                >

            </div>

        </div>


        <div class="form-group">

            <label for="notes">
                Interview Instructions / Notes
            </label>

            <textarea
                id="notes"
                name="notes"
                rows="5"
                placeholder="Enter instructions for the interview panel..."
            >{{ old('notes') }}</textarea>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Interview Panel
                </h2>

                <p>
                    Select the users who will participate in the interview.
                </p>

            </div>

        </div>


        @if($users->count())

            <div class="panel-member-list">

                @foreach($users as $user)

                    <label class="panel-member-option">

                        <input
                            type="checkbox"
                            name="panel_members[]"
                            value="{{ $user->id }}"
                        >

                        <span>

                            <strong>
                                {{ $user->name }}
                            </strong>

                            <small>
                                {{ $user->email }}
                            </small>

                        </span>

                    </label>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <h3>
                    No users available
                </h3>

                <p>
                    Create users before assigning an interview panel.
                </p>

            </div>

        @endif

    </div>


    <div class="form-actions">

        <a
            href="{{ route(
                'applications.show',
                $application
            ) }}"
            class="btn btn-secondary"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Schedule Interview
        </button>

    </div>

</form>

@endsection