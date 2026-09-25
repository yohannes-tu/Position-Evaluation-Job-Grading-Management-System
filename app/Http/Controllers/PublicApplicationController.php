<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Applicant;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\RecruitmentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicApplicationController extends Controller
{
    /**
     * Display the application form.
     */
    public function create(Vacancy $vacancy): View
    {
        abort_unless(
            $vacancy->status === 'published',
            404
        );

        abort_unless(
            $vacancy->closing_date &&
            $vacancy->closing_date->gte(now()->startOfDay()),
            404
        );

        $vacancy->load([
            'position.department',
            'position.approvedGrade',
        ]);

        return view(
            'public.applications.create',
            compact('vacancy')
        );
    }


    /**
     * Store a new public application.
     */
    public function store(
        Request $request,
        Vacancy $vacancy
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Vacancy validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $vacancy->status === 'published',
            404
        );

        abort_unless(
            $vacancy->closing_date &&
            $vacancy->closing_date->gte(now()->startOfDay()),
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate applicant information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'gender' => [
                'nullable',
                'in:male,female,other',
            ],

            'national_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'region' => [
                'nullable',
                'string',
                'max:100',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'cover_letter' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'declaration' => [
                'required',
                'accepted',
            ],

            'documents' => [
                'nullable',
                'array',
            ],

            'documents.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],

        ], [

            'declaration.required' =>
                'You must accept the declaration before submitting your application.',

            'declaration.accepted' =>
                'You must confirm that the information provided is accurate.',

            'documents.*.mimes' =>
                'Documents must be PDF, DOC, DOCX, JPG, JPEG, or PNG files.',

            'documents.*.max' =>
                'Each document must not exceed 5 MB.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate application
        |--------------------------------------------------------------------------
        |
        | The same email cannot apply to the same vacancy more than once.
        |
        */

        $existingApplication = Application::where(
            'vacancy_id',
            $vacancy->id
        )
            ->whereHas('applicant', function ($query) use ($validated) {
                $query->where(
                    'email',
                    strtolower(trim($validated['email']))
                );
            })
            ->exists();

        if ($existingApplication) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'An application using this email address has already been submitted for this vacancy.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create applicant + application
        |--------------------------------------------------------------------------
        */

        $application = DB::transaction(function () use (
            $validated,
            $request,
            $vacancy
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create or update applicant
            |--------------------------------------------------------------------------
            */

            $applicant = Applicant::updateOrCreate(
                [
                    'email' => strtolower(
                        trim($validated['email'])
                    ),
                ],
                [
                    'first_name' =>
                        trim($validated['first_name']),

                    'middle_name' =>
                        isset($validated['middle_name'])
                            ? trim($validated['middle_name'])
                            : null,

                    'last_name' =>
                        trim($validated['last_name']),

                    'phone' =>
                        trim($validated['phone']),

                    'date_of_birth' =>
                        $validated['date_of_birth'] ?? null,

                    'gender' =>
                        $validated['gender'] ?? null,

                    'national_id' =>
                        isset($validated['national_id'])
                            ? trim($validated['national_id'])
                            : null,

                    'address' =>
                        $validated['address'] ?? null,

                    'city' =>
                        isset($validated['city'])
                            ? trim($validated['city'])
                            : null,

                    'region' =>
                        isset($validated['region'])
                            ? trim($validated['region'])
                            : null,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Generate application number
            |--------------------------------------------------------------------------
            */

            do {

                $applicationNumber =
                    'APP-' .
                    now()->format('Y') .
                    '-' .
                    strtoupper(
                        Str::random(8)
                    );

            } while (
                Application::where(
                    'application_number',
                    $applicationNumber
                )->exists()
            );


            /*
            |--------------------------------------------------------------------------
            | Create application
            |--------------------------------------------------------------------------
            */

            $application = Application::create([

                'vacancy_id' =>
                    $vacancy->id,

                'applicant_id' =>
                    $applicant->id,

                'application_number' =>
                    $applicationNumber,

                'submitted_at' =>
                    now(),

                'status' =>
                    'submitted',

                'cover_letter' =>
                    $validated['cover_letter'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Store uploaded documents
            |--------------------------------------------------------------------------
            */

            $uploadedDocuments =
                $request->file('documents', []);

            $requiredDocuments =
                $vacancy->required_documents ?? [];


            foreach (
                $uploadedDocuments as $index => $file
            ) {

                if (!$file) {
                    continue;
                }


                $documentType =
                    $requiredDocuments[$index]
                    ?? 'Supporting Document';


                $path = $file->store(
                    'applications/' .
                    $application->id,
                    'public'
                );


                $application->documents()->create([

                    'document_type' =>
                        $documentType,

                    'original_name' =>
                        $file->getClientOriginalName(),

                    'file_path' =>
                        $path,

                    'mime_type' =>
                        $file->getMimeType(),

                    'file_size' =>
                        $file->getSize(),

                ]);
            }


            return $application;
        });


        User::whereIn('role', [
            'admin',
            'hr_admin',
            'recruiter',
        ])->get()->each(function (User $user) use ($application) {
            $user->notify(new RecruitmentNotification(
                'New Application Submitted',
                'A new application has been submitted for ' .
                ($application->vacancy->position->title ?? 'a vacancy') . '.',
                route('applications.show', $application)
            ));
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect to confirmation
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'public.applications.confirmation',
                $application
            )
            ->with(
                'success',
                'Your application has been submitted successfully.'
            );
    }


    /**
     * Display application confirmation.
     */
    public function confirmation(
        Application $application
    ): View {

        $application->load([
            'vacancy.position.department',
            'applicant',
            'documents',
        ]);

        return view(
            'public.applications.confirmation',
            compact('application')
        );
    }
}