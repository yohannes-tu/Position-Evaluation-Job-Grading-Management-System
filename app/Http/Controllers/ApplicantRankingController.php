<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicantRankingController extends Controller
{
    public function index(
    Request $request,
    Vacancy $vacancy
): View {
    $query = Application::with([
        'applicant',
        'interviews',
    ])
        ->where('vacancy_id', $vacancy->id)
        ->whereNotIn('status', [
            'rejected',
            'withdrawn',
        ]);

    if ($request->filled('status')) {
        $query->where(
            'status',
            $request->input('status')
        );
    }

    $applications = $query
        ->get()
        ->map(function ($application) {
            $applicationScore = (float) (
                $application->score ?? 0
            );

            $interview = $application->interviews
                ->where('status', 'completed')
                ->sortByDesc('id')
                ->first();

            $interviewScore = $interview
                ? (float) ($interview->total_score ?? 0)
                : 0;

            $finalScore =
                ($applicationScore * 0.40)
                +
                ($interviewScore * 0.60);

            $application->ranking_application_score =
                round($applicationScore, 2);

            $application->ranking_interview_score =
                round($interviewScore, 2);

            $application->ranking_final_score =
                round($finalScore, 2);

            $application->ranking_interview =
                $interview;

            return $application;
        });

    if ($request->filled('minimum_score')) {
        $minimumScore = (float) $request->input(
            'minimum_score'
        );

        $applications = $applications
            ->filter(function ($application) use ($minimumScore) {
                return $application->ranking_final_score >= $minimumScore;
            });
    }

    $applications = $applications
        ->sortByDesc('ranking_final_score')
        ->values()
        ->map(function ($application, $index) {
            $application->ranking_position =
                $index + 1;

            return $application;
        });

    return view(
        'applicant-rankings.index',
        compact(
            'vacancy',
            'applications'
        )
    );
}}