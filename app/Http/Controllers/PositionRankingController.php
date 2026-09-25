<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionRankingController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with([
            'department',
            'evaluations' => function ($query) {
                $query->where('status', 'completed')
                    ->latest('evaluated_at');
            }
        ]);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $positions = $query->get();

        $rankings = $positions
            ->map(function ($position) {

                $evaluation = $position->evaluations->first();

                return [
                    'position' => $position,
                    'score' => $evaluation?->total_score ?? 0,
                    'evaluation' => $evaluation,
                ];
            })
            ->sortByDesc('score')
            ->values()
            ->map(function ($item, $index) {

                $item['rank'] = $index + 1;

                return $item;
            });

        return view('position-rankings.index', compact('rankings'));
    }
}