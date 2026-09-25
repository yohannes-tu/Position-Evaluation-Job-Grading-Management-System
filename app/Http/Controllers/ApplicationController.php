<?php

namespace App\Http\Controllers;
use App\Models\ApplicationDocument;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;
use App\Notifications\RecruitmentNotification;

class ApplicationController extends Controller
{
    /**
     * Display applications.
     */
    public function index(Request $request): View
    {
        $query = Application::with([
            'applicant',
            'vacancy.position.department',
        ])->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($query) use ($search) {

                $query->where(
                    'application_number',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas('applicant', function ($query) use ($search) {

                    $query->where(
                        'first_name',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $search . '%'
                    );
                });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Vacancy filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('vacancy_id')) {

            $query->where(
                'vacancy_id',
                $request->vacancy_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $applications = $query
            ->paginate(15)
            ->withQueryString();


        $vacancies = Vacancy::with('position')
            ->orderByDesc('created_at')
            ->get();


        return view(
            'applications.index',
            compact(
                'applications',
                'vacancies'
            )
        );
    }


    /**
     * Display an application.
     */
    public function show(Application $application): View
    {
        $application->load([
            'applicant',
            'vacancy.position.department',
            'vacancy.position.approvedGrade',
            'documents',
        ]);

        return view(
            'applications.show',
            compact('application')
        );
    }


    /**
     * Update recruitment status.
     */
    public function updateStatus(
        Request $request,
        Application $application
    ): RedirectResponse {

        $validated = $request->validate([

            'status' => [
                'required',
                'in:under_review,shortlisted,interview,selected,rejected',
            ],

            'recruiter_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'screening_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'interview_at' => [
                'nullable',
                'date',
            ],

            'rejection_reason' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        $status = $validated['status'];


        /*
        |--------------------------------------------------------------------------
        | Rejection requires a reason
        |--------------------------------------------------------------------------
        */

        if (
            $status === 'rejected' &&
            empty(trim($validated['rejection_reason'] ?? ''))
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A rejection reason is required.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Interview requires interview date
        |--------------------------------------------------------------------------
        */

        if (
            $status === 'interview' &&
            empty($validated['interview_at'] ?? null)
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please provide the interview date and time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Build update data
        |--------------------------------------------------------------------------
        */

        $update = [

            'status' =>
                $status,

            'recruiter_notes' =>
                $validated['recruiter_notes'] ?? null,

            'screening_score' =>
                $validated['screening_score'] ?? null,

        ];


        /*
        |--------------------------------------------------------------------------
        | Status timestamps
        |--------------------------------------------------------------------------
        */

        if ($status === 'shortlisted') {

            $update['shortlisted_at'] =
                $application->shortlisted_at
                ?? now();
        }


        if ($status === 'interview') {

            $update['interview_at'] =
                $validated['interview_at'];
        }


        if ($status === 'selected') {

            $update['selected_at'] =
                $application->selected_at
                ?? now();
        }


        if ($status === 'rejected') {

            $update['rejected_at'] =
                now();

            $update['rejection_reason'] =
                trim(
                    $validated['rejection_reason']
                );
        }


        $application->update($update);


        return back()->with(
            'success',
            'Application status updated successfully.'
        );
    }


    /**
     * Download an application document.
     */
    public function downloadDocument(
        Application $application,
        ApplicationDocument $document
    ) {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        abort_unless(
            $document->application_id === $application->id,
            404
        );

        abort_unless(
            $disk->exists(
                $document->file_path
            ),
            404
        );

        return $disk->download(
            $document->file_path,
            $document->original_name
        );
    }

    public function select(
    Application $application
): RedirectResponse {
    $application->load('vacancy');

    $application->update([
        'status' => 'selected',
    ]);

    return back()->with(
        'success',
        'Applicant selected successfully.'
    );
}

public function reject(
    Application $application
): RedirectResponse {
    $application->update([
        'status' => 'rejected',
    ]);

    return back()->with(
        'success',
        'Applicant rejected successfully.'
    );
}
}