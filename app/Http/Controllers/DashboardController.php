<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\PositionEvaluation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $evaluatedPositions = PositionEvaluation::query()
            ->where('status', 'completed')
            ->distinct('position_id')
            ->count('position_id');

        $statistics = [
            'positions' => Position::count(),
            'evaluated_positions' => $evaluatedPositions,
            'pending_evaluations' => Position::whereDoesntHave(
                'evaluations',
                fn ($query) => $query->where('status', 'completed')
            )->count(),
            'departments' => Department::count(),
        ];

        $recentEvaluations = PositionEvaluation::with([
            'position',
            'evaluator',
        ])
            ->where('status', 'completed')
            ->latest('evaluated_at')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'statistics' => $statistics,
            'recentEvaluations' => $recentEvaluations,
        ]);
    }
}