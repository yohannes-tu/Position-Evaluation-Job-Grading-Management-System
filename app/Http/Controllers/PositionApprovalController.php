<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionApprovalController extends Controller
{
    /**
     * Submit position for evaluation.
     */
    public function submit(
        Position $position
    ): RedirectResponse {
        if ($position->status !== 'draft') {
            return back()->with(
                'error',
                'Only draft positions can be submitted.'
            );
        }

        $position->update([
            'status' => 'submitted',
        ]);

        return back()->with(
            'success',
            'Position submitted successfully.'
        );
    }

    /**
     * Start evaluation.
     */
    public function startEvaluation(
        Position $position
    ): RedirectResponse {
        if ($position->status !== 'submitted') {
            return back()->with(
                'error',
                'Only submitted positions can enter evaluation.'
            );
        }

        $position->update([
            'status' => 'under_evaluation',
        ]);

        return back()->with(
            'success',
            'Position moved to evaluation.'
        );
    }

    /**
 * Move completed evaluation to committee review.
 */
public function committeeReview(
    Position $position
): RedirectResponse {

    if ($position->status !== 'evaluated') {
        return back()->with(
            'error',
            'Only evaluated positions can be sent to committee review.'
        );
    }

    $evaluation = $position->evaluations()
        ->where('status', 'completed')
        ->latest()
        ->first();

    if (!$evaluation) {
        return back()->with(
            'error',
            'The position must have a completed evaluation before committee review.'
        );
    }

    $position->update([
        'status' => 'committee_review',
    ]);

    return back()->with(
        'success',
        'Position moved to committee review.'
    );
}

    /**
     * Move position to HR review.
     */
    public function hrReview(
        Position $position
    ): RedirectResponse {
        if ($position->status !== 'committee_review') {
            return back()->with(
                'error',
                'Only positions reviewed by the committee can move to HR review.'
            );
        }

        $position->update([
            'status' => 'hr_review',
        ]);

        return back()->with(
            'success',
            'Position moved to HR review.'
        );
    }

    /**
     * Approve position and determine grade.
     */
    public function approve(
        Request $request,
        Position $position
    ): RedirectResponse {
        if ($position->status !== 'hr_review') {
            return back()->with(
                'error',
                'Only positions in HR review can be approved.'
            );
        }

        $evaluation = $position->evaluations()
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$evaluation) {
            return back()->with(
                'error',
                'A completed evaluation is required before approval.'
            );
        }

        $grade = Grade::where('is_active', true)
            ->where(
                'min_score',
                '<=',
                $evaluation->total_score
            )
            ->where(
                'max_score',
                '>=',
                $evaluation->total_score
            )
            ->first();

        if (!$grade) {
            return back()->with(
                'error',
                'No grade matches the evaluation score. Please configure the grade ranges first.'
            );
        }

        $validated = $request->validate([
            'approval_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $position->update([
            'status' => 'approved',
            'approved_grade_id' => $grade->id,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'] ?? null,
        ]);

        return back()->with(
            'success',
            'Position approved successfully as ' . $grade->name . '.'
        );
    }

    /**
     * Reject position.
     */
    public function reject(
        Request $request,
        Position $position
    ): RedirectResponse {
        $validated = $request->validate([
            'approval_notes' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'approval_notes.required' =>
                'A rejection reason is required.',
        ]);

        $position->update([
            'status' => 'rejected',
            'approval_notes' => $validated['approval_notes'],
        ]);

        return back()->with(
            'success',
            'Position rejected and returned for revision.'
        );
    }
}