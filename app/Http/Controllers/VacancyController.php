<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class VacancyController extends Controller
{
    /**
     * Display a listing of vacancies.
     */
    public function index(Request $request): View
    {
        $query = Vacancy::with(['position.department'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('vacancy_code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('employment_type', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($positionQuery) use ($search) {
                        $positionQuery
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $vacancies = $query->paginate(10)->withQueryString();

        return view('vacancies.index', compact('vacancies'));
    }

    /**
     * Show vacancy creation form.
     */
    public function create(): View
{
    $positions = Position::with([
            'department',
            'approvedGrade',
        ])
        ->where('is_active', true)
        ->where('status', 'approved')
        ->whereNotNull('approved_grade_id')
        ->orderBy('title')
        ->get();

    return view(
        'vacancies.create',
        compact('positions')
    );
}
    /**
     * Store a new vacancy.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position_id' => [
    'required',
    'exists:positions,id',
    function ($attribute, $value, $fail) {
        $position = Position::find($value);

        if (!$position) {
            $fail('The selected position does not exist.');
            return;
        }

        if ($position->status !== 'approved') {
            $fail(
                'A vacancy can only be created for an approved position.'
            );
        }

        if (!$position->approved_grade_id) {
            $fail(
                'The selected position does not have an approved grade.'
            );
        }

        if (!$position->is_active) {
            $fail(
                'The selected position is inactive.'
            );
        }
    },
],

            'vacancy_code' => [
                'required',
                'string',
                'max:100',
                'unique:vacancies,vacancy_code',
            ],

            'number_of_openings' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            'posting_date' => [
                'required',
                'date',
            ],

            'closing_date' => [
                'required',
                'date',
                'after_or_equal:posting_date',
            ],

            'employment_type' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'application_instructions' => [
                'nullable',
                'string',
            ],

            'required_documents' => [
                'nullable',
            
            ],

            'show_salary' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['show_salary'] = $request->boolean('show_salary');

/*
|--------------------------------------------------------------------------
| Convert required documents from textarea to array
|--------------------------------------------------------------------------
*/

if ($request->filled('required_documents')) {

    $documents = $request->input('required_documents');

    if (is_array($documents)) {

        $validated['required_documents'] = collect($documents)
            ->flatten()
            ->map(fn ($document) => trim((string) $document))
            ->filter()
            ->values()
            ->toArray();

    } else {

        $validated['required_documents'] = collect(
            preg_split(
                '/\r\n|\r|\n/',
                (string) $documents
            )
        )
            ->map(fn ($document) => trim($document))
            ->filter()
            ->values()
            ->toArray();
    }

} else {

    $validated['required_documents'] = [];
}

// New vacancies always begin as draft.
$validated['status'] = 'draft';

$vacancy = Vacancy::create($validated);
        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with('success', 'Vacancy created successfully.');
    }

    /**
     * Display vacancy details.
     */
    public function show(Vacancy $vacancy): View
    {
        $vacancy->load([
            'position.department',
        ]);

        return view('vacancies.show', compact('vacancy'));
    }

    /**
     * Show vacancy edit form.
     */
    public function edit(Vacancy $vacancy): View|RedirectResponse
    {
        if (in_array($vacancy->status, ['published', 'closed', 'cancelled'])) {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with('error', 'This vacancy can no longer be edited.');
        }

        $positions = Position::with('department')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view(
            'vacancies.edit',
            compact('vacancy', 'positions')
        );
    }

    /**
     * Update vacancy.
     */
    public function update(
        Request $request,
        Vacancy $vacancy
    ): RedirectResponse {
        if (in_array($vacancy->status, ['published', 'closed', 'cancelled'])) {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Published, closed, or cancelled vacancies cannot be edited.'
                );
        }

        $validated = $request->validate([
            'position_id' => [
    'required',
    'exists:positions,id',
    function ($attribute, $value, $fail) {
        $position = Position::find($value);

        if (!$position) {
            $fail('The selected position does not exist.');
            return;
        }

        if ($position->status !== 'approved') {
            $fail(
                'A vacancy can only use an approved position.'
            );
        }

        if (!$position->approved_grade_id) {
            $fail(
                'The selected position does not have an approved grade.'
            );
        }

        if (!$position->is_active) {
            $fail(
                'The selected position is inactive.'
            );
        }
    },
],

            'vacancy_code' => [
                'required',
                'string',
                'max:100',
                'unique:vacancies,vacancy_code,' . $vacancy->id,
            ],

            'number_of_openings' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            'posting_date' => [
                'required',
                'date',
            ],

            'closing_date' => [
                'required',
                'date',
                'after_or_equal:posting_date',
            ],

            'employment_type' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'application_instructions' => [
                'nullable',
                'string',
            ],

            'required_documents' => [
                'nullable',
            
            ],

            'show_salary' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['show_salary'] = $request->boolean('show_salary');

/*
|--------------------------------------------------------------------------
| Convert required documents from textarea to array
|--------------------------------------------------------------------------
*/

if ($request->filled('required_documents')) {

    $documents = $request->input('required_documents');

    if (is_array($documents)) {

        $validated['required_documents'] = collect($documents)
            ->flatten()
            ->map(fn ($document) => trim((string) $document))
            ->filter()
            ->values()
            ->toArray();

    } else {

        $validated['required_documents'] = collect(
            preg_split(
                '/\r\n|\r|\n/',
                (string) $documents
            )
        )
            ->map(fn ($document) => trim($document))
            ->filter()
            ->values()
            ->toArray();
    }

} else {

    $validated['required_documents'] = [];
}

$vacancy->update($validated);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with('success', 'Vacancy updated successfully.');
    }

    /**
     * Delete vacancy.
     */
    public function destroy(Vacancy $vacancy): RedirectResponse
    {
        if ($vacancy->status === 'published') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Published vacancies cannot be deleted.'
                );
        }

        if ($vacancy->status === 'closed') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Closed vacancies cannot be deleted.'
                );
        }

        $vacancy->delete();

        return redirect()
            ->route('vacancies.index')
            ->with('success', 'Vacancy deleted successfully.');
    }

    /**
     * Submit vacancy for approval.
     */
    public function submitForApproval(
        Vacancy $vacancy
    ): RedirectResponse {
        if ($vacancy->status !== 'draft') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Only draft vacancies can be submitted for approval.'
                );
        }

        $vacancy->update([
            'status' => 'pending_approval',
        ]);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with(
                'success',
                'Vacancy submitted for approval.'
            );
    }

    /**
     * Approve vacancy.
     */
    public function approve(
        Vacancy $vacancy
    ): RedirectResponse {
        if ($vacancy->status !== 'pending_approval') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Only vacancies pending approval can be approved.'
                );
        }

        $vacancy->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with(
                'success',
                'Vacancy approved successfully.'
            );
    }

    /**
     * Publish vacancy.
     */
    public function publish(
        Vacancy $vacancy
    ): RedirectResponse {
        if ($vacancy->status !== 'approved') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Only approved vacancies can be published.'
                );
        }

        $vacancy->update([
            'status' => 'published',
        ]);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with(
                'success',
                'Vacancy published successfully.'
            );
    }

    /**
     * Close vacancy.
     */
    public function close(
        Vacancy $vacancy
    ): RedirectResponse {
        if ($vacancy->status !== 'published') {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'Only published vacancies can be closed.'
                );
        }

        $vacancy->update([
            'status' => 'closed',
        ]);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with(
                'success',
                'Vacancy closed successfully.'
            );
    }

    /**
     * Cancel vacancy.
     */
    public function cancel(
        Vacancy $vacancy
    ): RedirectResponse {
        if (
            !in_array(
                $vacancy->status,
                ['draft', 'pending_approval', 'approved']
            )
        ) {
            return redirect()
                ->route('vacancies.show', $vacancy)
                ->with(
                    'error',
                    'This vacancy cannot be cancelled in its current state.'
                );
        }

        $vacancy->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('vacancies.show', $vacancy)
            ->with(
                'success',
                'Vacancy cancelled successfully.'
            );
    }
}