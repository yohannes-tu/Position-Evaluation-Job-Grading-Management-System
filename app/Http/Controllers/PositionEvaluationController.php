<?php

namespace App\Http\Controllers;

use App\Models\EvaluationFactor;
use App\Models\Grade;
use App\Models\Position;
use App\Models\PositionEvaluation;
use App\Models\PositionEvaluationFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PositionEvaluationController extends Controller
{
    /**
     * Display the evaluation form.
     */
    public function create(Position $position)
    {
        $factors = EvaluationFactor::with('criteria')
            ->orderBy('id')
            ->get();

        return view(
            'positions.evaluate',
            compact('position', 'factors')
        );

    }

    /**
     * Store and calculate a position evaluation.
     */
    public function store(Request $request, Position $position)
    {
        $factors = EvaluationFactor::with('criteria')
            ->orderBy('id')
            ->get();

        if ($factors->isEmpty()) {
            return back()
                ->withErrors([
                    'evaluation' =>
                        'No evaluation factors have been configured yet.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validate that every factor has a selected criterion
        |--------------------------------------------------------------------------
        |
        | The evaluation form submits values like:
        |
        | factor_1 = 3
        | factor_2 = 5
        | factor_3 = 11
        |
        |--------------------------------------------------------------------------
        */

        foreach ($factors as $factor) {

            $field = 'factor_' . $factor->id;

            if (!$request->filled($field)) {
                return back()
                    ->withErrors([
                        $field =>
                            "Please select a criterion for {$factor->name}."
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create evaluation and calculate scores
        |--------------------------------------------------------------------------
        */

        $evaluation = DB::transaction(function () use (
            $request,
            $position,
            $factors
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create parent evaluation
            |--------------------------------------------------------------------------
            */

            $evaluation = PositionEvaluation::create([
                'position_id' => $position->id,
                'total_score' => 0,
                'status' => 'draft',
                'evaluated_by' => Auth::id(),
                'evaluated_at' => null,
            ]);

            $totalScore = 0;

            /*
            |--------------------------------------------------------------------------
            | Calculate each factor
            |--------------------------------------------------------------------------
            */

            foreach ($factors as $factor) {

                $field = 'factor_' . $factor->id;

                $criterionId = $request->input($field);

                /*
                |--------------------------------------------------------------------------
                | Find selected criterion
                |--------------------------------------------------------------------------
                */

                $criterion = $factor->criteria
                    ->firstWhere('id', (int) $criterionId);

                /*
                |--------------------------------------------------------------------------
                | Security check
                |--------------------------------------------------------------------------
                |
                | Make sure the submitted criterion actually belongs
                | to the selected evaluation factor.
                |--------------------------------------------------------------------------
                */

                if (!$criterion) {

                    abort(
                        422,
                        "Invalid criterion selected for {$factor->name}."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Raw criterion score
                |--------------------------------------------------------------------------
                */

                $score = (float) $criterion->points;

                /*
                |--------------------------------------------------------------------------
                | Factor weight
                |--------------------------------------------------------------------------
                */

                $weight = (float) $factor->weight;

                /*
                |--------------------------------------------------------------------------
                | Maximum factor score
                |--------------------------------------------------------------------------
                */

                $maxScore = (float) $factor->max_score;

                /*
                |--------------------------------------------------------------------------
                | Prevent division by zero
                |--------------------------------------------------------------------------
                */

                if ($maxScore <= 0) {

                    abort(
                        422,
                        "The maximum score for {$factor->name} must be greater than zero."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate weighted score
                |--------------------------------------------------------------------------
                |
                | Example:
                |
                | Criterion score = 15
                | Maximum score = 20
                | Weight = 25%
                |
                | Weighted score:
                |
                | (15 / 20) × 25 = 18.75
                |
                |--------------------------------------------------------------------------
                */

                $weightedScore =
                    ($score / $maxScore) * $weight;

                /*
                |--------------------------------------------------------------------------
                | Add to total
                |--------------------------------------------------------------------------
                */

                $totalScore += $weightedScore;

                /*
                |--------------------------------------------------------------------------
                | Save factor evaluation
                |--------------------------------------------------------------------------
                */

                PositionEvaluationFactor::create([
                    'position_evaluation_id' => $evaluation->id,
                    'evaluation_factor_id' => $factor->id,
                    'evaluation_criterion_id' => $criterion->id,
                    'score' => $score,
                    'weight' => $weight,
                    'weighted_score' => $weightedScore,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Round total score
            |--------------------------------------------------------------------------
            */

            $totalScore = round($totalScore, 2);

            /*
            |--------------------------------------------------------------------------
            | Complete evaluation
            |--------------------------------------------------------------------------
            */

           $evaluation->update([
    'total_score' => $totalScore,
    'status' => 'completed',
    'evaluated_at' => now(),
]);

$position->update([
    'status' => 'evaluated',
]);

            return $evaluation;
        });

        /*
        |--------------------------------------------------------------------------
        | Find recommended grade
        |--------------------------------------------------------------------------
        */

        $grade = Grade::query()
            ->where('is_active', true)
            ->where('min_score', '<=', $evaluation->total_score)
            ->where('max_score', '>=', $evaluation->total_score)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Load evaluation relationships
        |--------------------------------------------------------------------------
        */

        $evaluation->load([
            'position',
            'evaluator',
            'factors.evaluationFactor',
            'factors.criterion',
        ]);


$totalScore = (float) $evaluation->total_score;

return redirect()
    ->route('positions.evaluation.show', [
        'position' => $position,
        'evaluation' => $evaluation,
    ])
    ->with('success', 'Position evaluation completed successfully.');

        
    }

    /**
     * Display a specific evaluation result.
     */
    public function show(
        Position $position,
        PositionEvaluation $evaluation
    ) {
        /*
        |--------------------------------------------------------------------------
        | Make sure evaluation belongs to position
        |--------------------------------------------------------------------------
        */

        if ($evaluation->position_id !== $position->id) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Load relationships
        |--------------------------------------------------------------------------
        */

        $evaluation->load([
            'position',
            'evaluator',
            'factors.evaluationFactor',
            'factors.criterion',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get total score
        |--------------------------------------------------------------------------
        */

        $totalScore = (float) $evaluation->total_score;

        /*
        |--------------------------------------------------------------------------
        | Find recommended grade
        |--------------------------------------------------------------------------
        */

        $grade = Grade::query()
            ->where('is_active', true)
            ->where('min_score', '<=', $totalScore)
            ->where('max_score', '>=', $totalScore)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Return result page
        |--------------------------------------------------------------------------
        */

        return view(
            'positions.evaluation-result',
            compact(
                'position',
                'evaluation',
                'totalScore',
                'grade'
            )
        );
    }

    /**
     * Display evaluation history for a position.
     */
    public function index(Position $position)
    {
        /*
        |--------------------------------------------------------------------------
        | Get all evaluations for this position
        |--------------------------------------------------------------------------
        */

        $evaluations = PositionEvaluation::with('evaluator')
            ->where('position_id', $position->id)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest completed evaluation
        |--------------------------------------------------------------------------
        */

        $latestEvaluation = $evaluations
            ->where('status', 'completed')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Rating
        |--------------------------------------------------------------------------
        */

        $latestRating = $latestEvaluation
            ? $this->getRating(
                (float) $latestEvaluation->total_score
            )
            : null;

        /*
        |--------------------------------------------------------------------------
        | Calculate position ranking
        |--------------------------------------------------------------------------
        |
        | Only the latest completed evaluation for each position
        | is considered.
        |--------------------------------------------------------------------------
        */

        $latestByPosition = PositionEvaluation::query()
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('position_id')
            ->map(function ($positionEvaluations) {
                return $positionEvaluations->first();
            })
            ->sortByDesc(function ($evaluation) {
                return (float) $evaluation->total_score;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Find current position ranking
        |--------------------------------------------------------------------------
        */

        $ranking = null;

        foreach ($latestByPosition as $index => $evaluation) {

            if ((int) $evaluation->position_id === (int) $position->id) {

                $ranking = $index + 1;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Return evaluation history
        |--------------------------------------------------------------------------
        */

        return view(
            'positions.evaluations',
            compact(
                'position',
                'evaluations',
                'latestEvaluation',
                'latestRating',
                'ranking'
            )
        );
    }

    /**
     * Delete an evaluation.
     */
    public function destroy(
        Position $position,
        PositionEvaluation $evaluation
    ) {
        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        */

        if ($evaluation->position_id !== $position->id) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete evaluation
        |--------------------------------------------------------------------------
        |
        | PositionEvaluationFactor records should be deleted automatically
        | because the migration uses cascadeOnDelete().
        |--------------------------------------------------------------------------
        */

        $evaluation->delete();

        /*
        |--------------------------------------------------------------------------
        | Return to evaluation history
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'positions.evaluations.index',
                $position
            )
            ->with(
                'success',
                'Evaluation deleted successfully.'
            );
    }

    /**
     * Convert score into a human-readable rating.
     */
    private function getRating(float $score): string
    {
        if ($score >= 90) {
            return 'Excellent';
        }

        if ($score >= 80) {
            return 'Very Good';
        }

        if ($score >= 70) {
            return 'Good';
        }

        if ($score >= 60) {
            return 'Fair';
        }

        return 'Needs Improvement';
    }

    /**
     * Display position rankings.
     */
    public function rankings()
    {
        /*
        |--------------------------------------------------------------------------
        | Get latest completed evaluation for every position
        |--------------------------------------------------------------------------
        */

        $evaluations = PositionEvaluation::with([
            'position.department',
            'evaluator',
        ])
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('position_id')
            ->map(function ($positionEvaluations) {
                return $positionEvaluations->first();
            })
            ->sortByDesc(function ($evaluation) {
                return (float) $evaluation->total_score;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Return rankings page
        |--------------------------------------------------------------------------
        */

        return view(
            'positions.rankings',
            compact('evaluations')
        );
    }

    /**
     * Display evaluation statistics.
     */
    public function statistics()
    {
        /*
        |--------------------------------------------------------------------------
        | Get completed evaluations
        |--------------------------------------------------------------------------
        */

        $evaluations = PositionEvaluation::with('position')
            ->where('status', 'completed')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Basic statistics
        |--------------------------------------------------------------------------
        */

        $totalEvaluated = $evaluations->count();

        $averageScore = $totalEvaluated > 0
            ? round((float) $evaluations->avg('total_score'), 2)
            : 0;

        $highestScore = $totalEvaluated > 0
            ? round((float) $evaluations->max('total_score'), 2)
            : 0;

        $lowestScore = $totalEvaluated > 0
            ? round((float) $evaluations->min('total_score'), 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Rating counts
        |--------------------------------------------------------------------------
        */

        $ratingCounts = [
            'Excellent' => 0,
            'Very Good' => 0,
            'Good' => 0,
            'Fair' => 0,
            'Needs Improvement' => 0,
        ];

        foreach ($evaluations as $evaluation) {

            $rating = $this->getRating(
                (float) $evaluation->total_score
            );

            if (isset($ratingCounts[$rating])) {
                $ratingCounts[$rating]++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Return statistics page
        |--------------------------------------------------------------------------
        */

        return view(
            'positions.statistics',
            compact(
                'evaluations',
                'totalEvaluated',
                'averageScore',
                'highestScore',
                'lowestScore',
                'ratingCounts'
            )
        );
    }
}