<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\InterviewCriterion;
use App\Models\InterviewScore;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\InterviewPanelMember;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    /**
     * Display interview list.
     */
    public function index(Request $request): View
    {
        $query = Interview::with([
            'application.applicant',
            'application.vacancy.position',
            'panelMembers.user',
        ])->latest('scheduled_at');

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->whereHas(
                'application.applicant',
                function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }
            );
        }

        $interviews = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'interviews.index',
            compact('interviews')
        );
    }

    /**
     * Show interview scheduling form.
     */
    public function create(Application $application): View
    {
        if (
            !in_array(
                $application->status,
                ['shortlisted', 'interview']
            )
        ) {
            abort(
                403,
                'Only shortlisted applicants can be scheduled for an interview.'
            );
        }

        $users = User::orderBy('name')->get();

        return view(
            'interviews.create',
            compact(
                'application',
                'users'
            )
        );
    }

    /**
 * Schedule an interview.
 */
public function store(
    Request $request,
    Application $application
): RedirectResponse {
    if (
        !in_array(
            $application->status,
            ['shortlisted', 'interview']
        )
    ) {
        return back()->with(
            'error',
            'Only shortlisted applicants can be scheduled for an interview.'
        );
    }

    $validated = $request->validate([
        'scheduled_at' => [
            'required',
            'date',
        ],

        'interview_type' => [
            'required',
            'in:in_person,online,phone',
        ],

        'location' => [
            'nullable',
            'string',
            'max:255',
        ],

        'meeting_link' => [
            'nullable',
            'url',
            'max:1000',
        ],

        'duration_minutes' => [
            'required',
            'integer',
            'min:15',
            'max:480',
        ],

        'panel_members' => [
            'required',
            'array',
            'min:1',
        ],

        'panel_members.*' => [
            'required',
            'exists:users,id',
        ],

        'panel_roles' => [
            'nullable',
            'array',
        ],

        'notes' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    $interview = Interview::create([
        'application_id' => $application->id,
        'scheduled_at' => $validated['scheduled_at'],
        'interview_type' => $validated['interview_type'],
        'location' => $validated['location'] ?? null,
        'meeting_link' => $validated['meeting_link'] ?? null,
        'duration_minutes' => $validated['duration_minutes'],
        'status' => 'scheduled',
        'recommendation' => 'pending',
        'notes' => $validated['notes'] ?? null,
        'created_by' => Auth::id(),
    ]);

    foreach (
        $validated['panel_members']
        as $index => $userId
    ) {
        InterviewPanelMember::create([
            'interview_id' => $interview->id,
            'user_id' => $userId,
            'role' =>
                $request->input(
                    "panel_roles.{$index}"
                ),
        ]);
    }

    $application->update([
        'status' => 'interview',
    ]);

    return redirect()
        ->route(
            'interviews.show',
            $interview
        )
        ->with(
            'success',
            'Interview scheduled successfully.'
        );
}

/**
 * Display interview details.
 */
public function show(Interview $interview): View
{
    $interview->load([
        'application.applicant',
        'application.vacancy.position',
        'panelMembers.user',
        'scores.panelMember.user',
    ]);

    return view(
        'interviews.show',
        compact('interview')
    );
}

/**
 * Mark interview as completed.
 */
public function complete(
    Request $request,
    Interview $interview
): RedirectResponse {
    if (
        !in_array(
            $interview->status,
            ['scheduled', 'confirmed']
        )
    ) {
        return back()->with(
            'error',
            'Only scheduled or confirmed interviews can be completed.'
        );
    }

    $validated = $request->validate([
        'notes' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    $interview->update([
        'status' => 'completed',
        'notes' => $validated['notes']
            ?? $interview->notes,
        'updated_by' => Auth::id(),
    ]);

    return back()->with(
        'success',
        'Interview marked as completed.'
    );
}
/**
 * Cancel interview.
 */
public function cancel(
    Request $request,
    Interview $interview
): RedirectResponse {
    $validated = $request->validate([
        'reason' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    $interview->update([
        'status' => 'cancelled',
        'notes' => trim(
            ($interview->notes
                ? $interview->notes . "\n\n"
                : ''
            ) .
            'Cancellation reason: ' .
            $validated['reason']
        ),
        'updated_by' => Auth::id(),
    ]);

    return back()->with(
        'success',
        'Interview cancelled.'
    );
}
public function scoring(
    Interview $interview
): View {
    $interview->load([
        'application.applicant',
        'application.vacancy.position',
        'panelMembers.user',
        'scores.panelMember.user',
        'scores.interviewCriterion',
    ]);

    $criteria = InterviewCriterion::where(
        'is_active',
        true
    )
        ->orderBy('sort_order')
        ->get();

    return view(
        'interviews.scoring',
        compact(
            'interview',
            'criteria'
        )
    );
}
public function saveScores(
    Request $request,
    Interview $interview
): RedirectResponse {
    if ($interview->status !== 'completed') {
        return back()->with(
            'error',
            'The interview must be completed before panel scores can be submitted.'
        );
    }

    $criteria = InterviewCriterion::where(
        'is_active',
        true
    )
        ->orderBy('sort_order')
        ->get();

    if ($criteria->isEmpty()) {
        return back()->with(
            'error',
            'No active interview scoring criteria are configured.'
        );
    }

    $panelMembers = $interview
        ->panelMembers()
        ->get();

    if ($panelMembers->isEmpty()) {
        return back()->with(
            'error',
            'At least one interview panel member is required.'
        );
    }

    $rules = [];

    foreach ($panelMembers as $member) {
        foreach ($criteria as $criterion) {
            $rules[
                "scores.{$member->id}.{$criterion->id}"
            ] = [
                'required',
                'numeric',
                'min:0',
                'max:' . $criterion->max_score,
            ];

            $rules[
                "comments.{$member->id}.{$criterion->id}"
            ] = [
                'nullable',
                'string',
                'max:2000',
            ];
        }
    }

    $validated = $request->validate($rules);

    /*
    |--------------------------------------------------------------------------
    | Save panel scores
    |--------------------------------------------------------------------------
    */

    InterviewScore::where(
        'interview_id',
        $interview->id
    )->delete();

    $panelTotals = [];

    foreach ($panelMembers as $member) {

        $panelTotal = 0;

        foreach ($criteria as $criterion) {

            $score = (float) (
                $validated['scores']
                [$member->id]
                [$criterion->id]
            );

            $weight = (float) $criterion->weight;

            $maxScore = (float) $criterion->max_score;

            $weightedScore =
                ($score / $maxScore) * $weight;

            InterviewScore::create([
                'interview_id' =>
                    $interview->id,

                'panel_member_id' =>
                    $member->id,

                'interview_criterion_id' =>
                    $criterion->id,

                'criterion' =>
                    $criterion->name,

                'score' =>
                    $score,

                'weight' =>
                    $weight,

                'weighted_score' =>
                    round(
                        $weightedScore,
                        2
                    ),

                'comments' =>
                    $validated['comments']
                    [$member->id]
                    [$criterion->id]
                    ?? null,
            ]);

            $panelTotal += $weightedScore;
        }

        $panelTotals[] = $panelTotal;
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate final interview score
    |--------------------------------------------------------------------------
    */

    $finalScore = count($panelTotals) > 0
        ? array_sum($panelTotals)
            / count($panelTotals)
        : 0;

    $interview->update([
        'total_score' =>
            round($finalScore, 2),

        'updated_by' =>
            Auth::id(),
    ]);

    return redirect()
        ->route(
            'interviews.show',
            $interview
        )
        ->with(
            'success',
            'Panel scores saved successfully. Final interview score: '
            . number_format($finalScore, 2)
        );
}
public function recommendation(
    Request $request,
    Interview $interview
): RedirectResponse {
    if ($interview->status !== 'completed') {
        return back()->with(
            'error',
            'Only completed interviews can receive recommendations.'
        );
    }

    if ($interview->total_score === null) {
        return back()->with(
            'error',
            'Please complete panel scoring before adding a recommendation.'
        );
    }

    $validated = $request->validate([
        'recommendation' => [
            'required',
            'in:recommended,reserve,not_recommended',
        ],
    ]);

    $interview->update([
        'recommendation' =>
            $validated['recommendation'],

        'updated_by' =>
            Auth::id(),
    ]);

    return back()->with(
        'success',
        'Interview recommendation updated successfully.'
    );
}
}