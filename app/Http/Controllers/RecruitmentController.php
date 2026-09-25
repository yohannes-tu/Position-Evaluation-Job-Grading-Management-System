<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\View\View;

class RecruitmentController extends Controller
{
    /**
     * Recruitment dashboard.
     */
    public function dashboard(): View
    {
        $totalApplications = Application::count();

        $submittedApplications = Application::where(
            'status',
            'submitted'
        )->count();

        $underReview = Application::where(
            'status',
            'under_review'
        )->count();

        $shortlisted = Application::where(
            'status',
            'shortlisted'
        )->count();

        $interviews = Application::where(
            'status',
            'interview'
        )->count();

        $selected = Application::where(
            'status',
            'selected'
        )->count();

        $rejected = Application::where(
            'status',
            'rejected'
        )->count();

        $activeVacancies = Vacancy::where(
            'status',
            'published'
        )->count();

        $recentApplications = Application::with([
            'applicant',
            'vacancy.position',
        ])
            ->latest()
            ->limit(10)
            ->get();

        $applicationsByStatus = [
            'Submitted' => $submittedApplications,
            'Under Review' => $underReview,
            'Shortlisted' => $shortlisted,
            'Interview' => $interviews,
            'Selected' => $selected,
            'Rejected' => $rejected,
        ];

        $topVacancies = Vacancy::with([
            'position',
        ])
            ->withCount('applications')
            ->whereHas('applications')
            ->orderByDesc('applications_count')
            ->limit(5)
            ->get();

        return view(
            'recruitment.dashboard',
            compact(
                'totalApplications',
                'submittedApplications',
                'underReview',
                'shortlisted',
                'interviews',
                'selected',
                'rejected',
                'activeVacancies',
                'recentApplications',
                'applicationsByStatus',
                'topVacancies'
            )
        );
    }
}