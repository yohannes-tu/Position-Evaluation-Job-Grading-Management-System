<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicVacancyController extends Controller
{
    /**
     * Display published vacancies.
     */
    public function index(Request $request): View
    {
        $query = Vacancy::with([
            'position.department',
            'position.approvedGrade',
        ])
            ->where('status', 'published')
            ->whereDate('closing_date', '>=', now()->toDateString());

        /*
        |--------------------------------------------------------------------------
        | Keyword Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $query->where(function ($q) use ($keyword) {
                $q->where('vacancy_code', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('position', function ($positionQuery) use ($keyword) {
                        $positionQuery
                            ->where('title', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Position Title
        |--------------------------------------------------------------------------
        */

        if ($request->filled('position_title')) {
            $title = trim($request->input('position_title'));

            $query->whereHas('position', function ($positionQuery) use ($title) {
                $positionQuery->where('title', 'like', "%{$title}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {
            $query->whereHas('position', function ($positionQuery) use ($request) {
                $positionQuery->where(
                    'department_id',
                    $request->input('department_id')
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Grade
        |--------------------------------------------------------------------------
        */

        if ($request->filled('grade_id')) {
            $query->whereHas('position', function ($positionQuery) use ($request) {
                $positionQuery->where(
                    'approved_grade_id',
                    $request->input('grade_id')
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {
            $location = trim($request->input('location'));

            $query->where(
                'location',
                'like',
                "%{$location}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Employment Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('employment_type')) {
            $query->where(
                'employment_type',
                $request->input('employment_type')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->input('sort', 'latest');

        switch ($sort) {
            case 'closing_soon':
                $query->orderBy('closing_date', 'asc');
                break;

            case 'oldest':
                $query->orderBy('posting_date', 'asc');
                break;

            default:
                $query->orderBy('posting_date', 'desc');
                break;
        }

        $vacancies = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $departments = \App\Models\Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $grades = \App\Models\Grade::where('is_active', true)
            ->orderBy('min_score')
            ->get();

        return view(
            'public.vacancies.index',
            compact(
                'vacancies',
                'departments',
                'grades'
            )
        );
    }

    /**
     * Display a single published vacancy.
     */
    public function show(Vacancy $vacancy): View
    {
        abort_unless(
            $vacancy->status === 'published',
            404
        );

        abort_unless(
            $vacancy->closing_date &&
            $vacancy->closing_date->gte(
                now()->startOfDay()
            ),
            404
        );

        $vacancy->load([
            'position.department',
            'position.approvedGrade',
        ]);

        return view(
            'public.vacancies.show',
            compact('vacancy')
        );
    }

    /**
     * Display public information about the related position.
     */
    public function position(Position $position): View
    {
        abort_unless(
            $position->status === 'approved',
            404
        );

        abort_unless(
            $position->is_active,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Only expose positions that have a published vacancy.
        |--------------------------------------------------------------------------
        */

        $hasPublishedVacancy = $position->vacancies()
            ->where('status', 'published')
            ->whereDate(
                'closing_date',
                '>=',
                now()->toDateString()
            )
            ->exists();

        abort_unless($hasPublishedVacancy, 404);

        $position->load([
            'department',
            'approvedGrade',
        ]);

        return view(
            'public.positions.show',
            compact('position')
        );
    }
}