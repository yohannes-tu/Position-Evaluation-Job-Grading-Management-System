<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Application::with([
            'applicant',
            'vacancy.position',
            'interviews',
            'hiringRecord',
        ])->latest('submitted_at');

        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'submitted_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'submitted_at',
                '<=',
                $request->date_to
            );
        }

        $applications = $query->paginate(15)->withQueryString();

        $vacancies = Vacancy::with('position')
            ->orderByDesc('created_at')
            ->get();

        $statuses = [
            'submitted',
            'under_review',
            'shortlisted',
            'interview',
            'selected',
            'hired',
            'rejected',
            'withdrawn',
        ];

        return view('recruitment.reports', compact(
            'applications',
            'vacancies',
            'statuses'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Application::with([
            'applicant',
            'vacancy.position',
            'interviews',
            'hiringRecord',
        ])->latest('submitted_at');

        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'submitted_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'submitted_at',
                '<=',
                $request->date_to
            );
        }

        $applications = $query->get();

        $filename = 'recruitment-report-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Application Number',
                'Applicant Name',
                'Email',
                'Phone',
                'Position',
                'Vacancy Code',
                'Application Date',
                'Status',
                'Application Score',
                'Interview Score',
                'Final Score',
                'Hiring Status',
            ]);

            foreach ($applications as $application) {
                $interviewScore = $application->interviews
                    ->where('status', 'completed')
                    ->sortByDesc('created_at')
                    ->first()?->total_score;

                $applicationScore = (float) (
                    $application->screening_score ?? 0
                );

                $interviewScoreValue = (float) (
                    $interviewScore ?? 0
                );

                $finalScore = (
                    $applicationScore * 0.40
                ) + (
                    $interviewScoreValue * 0.60
                );

                fputcsv($handle, [
                    $application->application_number,
                    $application->applicant->full_name ?? '',
                    $application->applicant->email ?? '',
                    $application->applicant->phone ?? '',
                    $application->vacancy->position->title ?? '',
                    $application->vacancy->vacancy_code ?? '',
                    $application->submitted_at?->format('Y-m-d'),
                    $application->status,
                    number_format($applicationScore, 2),
                    number_format($interviewScoreValue, 2),
                    number_format($finalScore, 2),
                    $application->hiringRecord
                        ? $application->hiringRecord->employment_status
                        : 'Not hired',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}