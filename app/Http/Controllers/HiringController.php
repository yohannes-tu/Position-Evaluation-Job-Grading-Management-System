<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\HiringRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HiringController extends Controller
{
    public function hire(
        Request $request,
        Application $application
    ): RedirectResponse {
        $validated = $request->validate([
            'notes' => [
                'nullable',
                'string',   
                'max:2000',
            ],
        ]);

        $application->load([
            'vacancy',
            'hiringRecord',
        ]);

        if ($application->hiringRecord) {
            return back()->with(
                'error',
                'This applicant has already been hired.'
            );
        }

        if (!in_array($application->status, [
            'selected',
            'shortlisted',
            'interview',
        ])) {
            return back()->with(
                'error',
                'Only selected or shortlisted applicants can be hired.'
            );
        }

        $vacancy = $application->vacancy;

        if (!$vacancy) {
            return back()->with(
                'error',
                'The vacancy connected to this application was not found.'
            );
        }

        $currentHiredCount = HiringRecord::where(
            'vacancy_id',
            $vacancy->id
        )->count();

        if (
            $currentHiredCount >=
            (int) $vacancy->number_of_openings
        ) {
            return back()->with(
                'error',
                'All available openings for this vacancy have already been filled.'
            );
        }

        DB::transaction(function () use (
            $application,
            $vacancy,
            $validated
        ) {
            HiringRecord::create([
                'application_id' => $application->id,
                'vacancy_id' => $vacancy->id,
                'hired_by' => Auth::id(),
                'hired_at' => now()->toDateString(),
                'employment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            $application->update([
                'status' => 'hired',
            ]);

            $hiredCount = HiringRecord::where(
                'vacancy_id',
                $vacancy->id
            )->count();

            if (
                $hiredCount >=
                (int) $vacancy->number_of_openings
            ) {
                $vacancy->update([
                    'status' => 'closed',
                ]);
            }
        });

        return back()->with(
            'success',
            'Applicant hired successfully.'
        );
    }

    public function cancelHiring(
        Application $application
    ): RedirectResponse {
        $application->load('hiringRecord');

        if (!$application->hiringRecord) {
            return back()->with(
                'error',
                'No hiring record exists for this applicant.'
            );
        }

        $vacancy = $application->hiringRecord->vacancy;

        DB::transaction(function () use (
            $application,
            $vacancy
        ) {
            $application->hiringRecord->delete();

            $application->update([
                'status' => 'selected',
            ]);

            if (
                $vacancy &&
                $vacancy->status === 'closed'
            ) {
                $vacancy->update([
                    'status' => 'published',
                ]);
            }
        });

        return back()->with(
            'success',
            'Hiring decision cancelled.'
        );
    }
}