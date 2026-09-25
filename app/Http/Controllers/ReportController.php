<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\PositionEvaluation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Reports dashboard.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Position evaluation report.
     */
    public function positionEvaluation(Request $request): View
    {
        $positions = Position::with('department')
            ->orderBy('title')
            ->get();

        $evaluation = null;
        $position = null;

        if ($request->filled('position_id')) {

            $position = Position::with('department')
                ->findOrFail($request->position_id);

            $evaluation = PositionEvaluation::with([
                'position',
                'evaluator',
                'factors.evaluationFactor',
                'factors.criterion',
            ])
                ->where('position_id', $position->id)
                ->latest()
                ->first();
        }

        return view(
            'reports.position-evaluation',
            compact(
                'positions',
                'position',
                'evaluation'
            )
        );
    }


    /**
 * Display the position inventory report.
 */
public function positionInventory(Request $request): View
{
    $departments = \App\Models\Department::orderBy('name')->get();

    $query = \App\Models\Position::with('department');

    // Search by title or code
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('code', 'like', '%' . $search . '%');
        });
    }

    // Department filter
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    // Status filter
    if ($request->filled('status')) {

        if ($request->status === 'active') {
            $query->where('is_active', true);
        }

        if ($request->status === 'inactive') {
            $query->where('is_active', false);
        }
    }

    $positions = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view(
        'reports.position-inventory',
        compact(
            'positions',
            'departments'
        )
    );
}
/**
 * Display grade distribution report.
 */
public function gradeDistribution(Request $request): View
{
    $departments = \App\Models\Department::orderBy('name')->get();

    $grades = \App\Models\Grade::where('is_active', true)
        ->orderBy('min_score')
        ->get();

    $query = \App\Models\PositionEvaluation::with([
        'position.department'
    ])
        ->where('status', 'completed');

    // Department filter
    if ($request->filled('department_id')) {
        $query->whereHas('position', function ($q) use ($request) {
            $q->where('department_id', $request->department_id);
        });
    }

    $evaluations = $query->get();

    $distribution = [];

    foreach ($grades as $grade) {

        $gradeEvaluations = $evaluations->filter(function ($evaluation) use ($grade) {

            return (float) $evaluation->total_score >= (float) $grade->min_score
                && (float) $evaluation->total_score <= (float) $grade->max_score;
        });

        $distribution[] = [
            'grade' => $grade,
            'count' => $gradeEvaluations->count(),
            'evaluations' => $gradeEvaluations,
        ];
    }

    $totalEvaluated = $evaluations->count();

    foreach ($distribution as &$item) {

        $item['percentage'] = $totalEvaluated > 0
            ? ($item['count'] / $totalEvaluated) * 100
            : 0;
    }

    return view(
        'reports.grade-distribution',
        compact(
            'departments',
            'distribution',
            'totalEvaluated'
        )
    );
}

/**
 * Evaluation progress report.
 */
public function evaluationProgress(Request $request): View
{
    $departments = \App\Models\Department::orderBy('name')->get();

    $query = \App\Models\PositionEvaluation::with([
        'position.department',
        'evaluator',
    ]);

    if ($request->filled('department_id')) {
        $query->whereHas('position', function ($q) use ($request) {
            $q->where('department_id', $request->department_id);
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $evaluations = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();

    $total = \App\Models\PositionEvaluation::count();

    $draft = \App\Models\PositionEvaluation::where(
        'status',
        'draft'
    )->count();

    $completed = \App\Models\PositionEvaluation::where(
        'status',
        'completed'
    )->count();

    return view(
        'reports.evaluation-progress',
        compact(
            'departments',
            'evaluations',
            'total',
            'draft',
            'completed'
        )
    );
}


/**
 * Evaluator performance report.
 */
public function evaluatorPerformance(Request $request): View
{
    $evaluators = \App\Models\User::whereHas(
        'positionEvaluations'
    )
        ->withCount([
            'positionEvaluations as total_evaluations',
            'positionEvaluations as completed_evaluations' => function ($query) {
                $query->where('status', 'completed');
            },
            'positionEvaluations as draft_evaluations' => function ($query) {
                $query->where('status', 'draft');
            },
        ])
        ->orderBy('name')
        ->get();

    return view(
        'reports.evaluator-performance',
        compact('evaluators')
    );
}
}