@extends('layouts.public')

@section('title', 'Vacancies')

@section('content')

<div class="page-header">
    <div>
        <h1>Career Opportunities</h1>
        <p>
            Explore current employment opportunities and apply for positions
            that match your qualifications.
        </p>
    </div>
</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('public.vacancies.index') }}"
        class="filter-form"
    >

        <div class="form-group">
            <label for="keyword">Keyword</label>

            <input
                type="text"
                id="keyword"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Search position, code or keyword..."
            >
        </div>

        <div class="form-group">
            <label for="position_title">Position Title</label>

            <input
                type="text"
                id="position_title"
                name="position_title"
                value="{{ request('position_title') }}"
                placeholder="e.g. Accountant"
            >
        </div>

        <div class="form-group">
            <label for="department_id">Department</label>

            <select
                id="department_id"
                name="department_id"
            >
                <option value="">All Departments</option>

                @foreach($departments as $department)
                    <option
                        value="{{ $department->id }}"
                        @selected(
                            request('department_id') == $department->id
                        )
                    >
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="grade_id">Grade</label>

            <select
                id="grade_id"
                name="grade_id"
            >
                <option value="">All Grades</option>

                @foreach($grades as $grade)
                    <option
                        value="{{ $grade->id }}"
                        @selected(
                            request('grade_id') == $grade->id
                        )
                    >
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="location">Location</label>

            <input
                type="text"
                id="location"
                name="location"
                value="{{ request('location') }}"
                placeholder="e.g. Addis Ababa"
            >
        </div>

        <div class="form-group">
            <label for="employment_type">Employment Type</label>

            <select
                id="employment_type"
                name="employment_type"
            >
                <option value="">All Types</option>

                <option
                    value="full_time"
                    @selected(request('employment_type') === 'full_time')
                >
                    Full Time
                </option>

                <option
                    value="part_time"
                    @selected(request('employment_type') === 'part_time')
                >
                    Part Time
                </option>

                <option
                    value="contract"
                    @selected(request('employment_type') === 'contract')
                >
                    Contract
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="sort">Sort By</label>

            <select id="sort" name="sort">
                <option
                    value="latest"
                    @selected(request('sort', 'latest') === 'latest')
                >
                    Latest Posted
                </option>

                <option
                    value="closing_soon"
                    @selected(request('sort') === 'closing_soon')
                >
                    Closing Soon
                </option>

                <option
                    value="oldest"
                    @selected(request('sort') === 'oldest')
                >
                    Oldest
                </option>
            </select>
        </div>

        <div class="filter-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Search Vacancies
            </button>

            <a
                href="{{ route('public.vacancies.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

</div>


<div class="page-section">

    <div class="section-header">

        <div>
            <h2>Available Vacancies</h2>

            <p>
                {{ $vacancies->total() }}
                {{ Str::plural('vacancy', $vacancies->total()) }}
                available
            </p>
        </div>

    </div>


    @if($vacancies->count())

        <div class="vacancy-list">

            @foreach($vacancies as $vacancy)

                <article class="vacancy-card">

                    <div class="vacancy-card-header">

                        <div>

                            <span class="status-badge status-success">
                                Published
                            </span>

                            <h3>
                                {{ $vacancy->position->title }}
                            </h3>

                            <p class="vacancy-code">
                                Vacancy Code:
                                {{ $vacancy->vacancy_code }}
                            </p>

                        </div>

                        @if($vacancy->position->approvedGrade)
                            <span class="grade-badge">
                                {{ $vacancy->position->approvedGrade->name }}
                            </span>
                        @endif

                    </div>


                    <div class="vacancy-meta">

                        <span>
                            🏢
                            {{ $vacancy->position->department->name ?? 'N/A' }}
                        </span>

                        <span>
                            📍
                            {{ $vacancy->location ?: 'Not specified' }}
                        </span>

                        <span>
                            💼
                            {{ ucwords(str_replace('_', ' ', $vacancy->employment_type)) }}
                        </span>

                        <span>
                            👥
                            {{ $vacancy->number_of_openings }}
                            {{ Str::plural('opening', $vacancy->number_of_openings) }}
                        </span>

                    </div>


                    <p class="vacancy-description">
                        {{ Str::limit(strip_tags($vacancy->description), 220) }}
                    </p>


                    <div class="vacancy-footer">

                        <div>

                            <small>
                                Posted:
                                {{ optional($vacancy->posting_date)->format('M d, Y') }}
                            </small>

                            <small>
                                Closing:
                                {{ optional($vacancy->closing_date)->format('M d, Y') }}
                            </small>

                        </div>

                        <a
                            href="{{ route('public.vacancies.show', $vacancy) }}"
                            class="btn btn-primary"
                        >
                            View Details
                        </a>

                    </div>

                </article>

            @endforeach

        </div>


        <div class="pagination-wrapper">
            {{ $vacancies->links() }}
        </div>

    @else

        <div class="empty-state">

            <div class="empty-state-icon">
                🔎
            </div>

            <h3>No vacancies found</h3>

            <p>
                No published vacancies match your current search criteria.
            </p>

            <a
                href="{{ route('public.vacancies.index') }}"
                class="btn btn-secondary"
            >
                Clear Filters
            </a>

        </div>

    @endif

</div>

@endsection