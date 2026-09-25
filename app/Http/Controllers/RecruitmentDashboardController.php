<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\HiringRecord;
use App\Models\Vacancy;
use Illuminate\View\View;

class RecruitmentDashboardController extends Controller
{
    public function index(): View
    {
        $totalVacancies = Vacancy::count();

        $activeVacancies = Vacancy::whereIn('status', [
            'approved',
            'published',
        ])->count();

        $totalApplications = Application::count();

        $shortlistedApplications = Application::where(
            'status',
            'shortlisted'
        )->count();

        $interviewApplications = Application::where(
            'status',
            'interview'
        )->count();

        $selectedApplications = Application::where(
            'status',
            'selected'
        )->count();

        $hiredApplications = Application::where(
            'status',
            'hired'
        )->count();

        $rejectedApplications = Application::where(
            'status',
            'rejected'
        )->count();

        $vacancies = Vacancy::with([
            'position.department',
            'hiringRecords',
        ])
            ->latest()
            ->get()
            ->map(function ($vacancy) {
                $hiredCount = $vacancy->hiringRecords->count();

                $openingCount = (int) (
                    $vacancy->number_of_openings ?? 0
                );

                $fillingPercentage = $openingCount > 0
                    ? min(
                        100,
                        round(
                            ($hiredCount / $openingCount) * 100,
                            2
                        )
                    )
                    : 0;

                $vacancy->hired_count = $hiredCount;
                $vacancy->filling_percentage =
                    $fillingPercentage;

                return $vacancy;
            });

        $recentHirings = HiringRecord::with([
            'application.applicant',
            'vacancy.position',
            'hiredBy',
        ])
            ->latest('hired_at')
            ->limit(10)
            ->get();

        return view(
            'recruitment.dashboard',
            compact(
                'totalVacancies',
                'activeVacancies',
                'totalApplications',
                'shortlistedApplications',
                'interviewApplications',
                'selectedApplications',
                'hiredApplications',
                'rejectedApplications',
                'vacancies',
                'recentHirings'
            )
        );
    }
}