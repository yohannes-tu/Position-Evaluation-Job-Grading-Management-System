<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCriterion;
use App\Models\EvaluationFactor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluationFactorController extends Controller
{
    public function index()
{
    $factors = EvaluationFactor::latest()->get();

    return view('evaluation-factors.index', compact('factors'));
}


    public function create()
    {
        return view('evaluation-factors.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:evaluation_factors,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'weight' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'max_score' => [
                'required',
                'numeric',
                'min:1',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        EvaluationFactor::create($validated);

        return redirect()
            ->route('evaluation-factors.index')
            ->with('success', 'Evaluation factor created successfully.');
    }


    public function edit(EvaluationFactor $evaluationFactor)
    {
        return view(
            'evaluation-factors.edit',
            compact('evaluationFactor')
        );
    }


    public function update(
        Request $request,
        EvaluationFactor $evaluationFactor
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('evaluation_factors', 'name')
                    ->ignore($evaluationFactor->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'weight' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'max_score' => [
                'required',
                'numeric',
                'min:1',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $evaluationFactor->update($validated);

        return redirect()
            ->route('evaluation-factors.index')
            ->with('success', 'Evaluation factor updated successfully.');
    }


    public function destroy(EvaluationFactor $evaluationFactor)
    {
        $evaluationFactor->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('evaluation-factors.index')
            ->with('success', 'Evaluation factor deactivated successfully.');
    }


    public function criteria(EvaluationFactor $evaluation_factor)
{
    $criteria = $evaluation_factor->criteria()->latest()->get();

    return view(
        'evaluation-factors.criteria',
        compact('evaluation_factor', 'criteria')
    );
}


public function createCriteria(EvaluationFactor $evaluation_factor)
{
    return view(
        'evaluation-factors.criteria-create',
        compact('evaluation_factor')
    );
}


public function storeCriteria(
    Request $request,
    EvaluationFactor $evaluation_factor
) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'points' => ['required', 'numeric', 'min:0'],
    ]);

    $evaluation_factor->criteria()->create($validated);

    return redirect()
        ->route(
            'evaluation-factors.criteria',
            $evaluation_factor
        )
        ->with('success', 'Evaluation criterion created successfully.');
}


public function editCriteria(
    EvaluationFactor $evaluation_factor,
    EvaluationCriterion $criterion
) {
    return view(
        'evaluation-factors.criteria-edit',
        compact('evaluation_factor', 'criterion')
    );
}


public function updateCriteria(
    Request $request,
    EvaluationFactor $evaluation_factor,
    EvaluationCriterion $criterion
) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'points' => ['required', 'numeric', 'min:0'],
    ]);

    $criterion->update($validated);

    return redirect()
        ->route(
            'evaluation-factors.criteria',
            $evaluation_factor
        )
        ->with('success', 'Evaluation criterion updated successfully.');
}


public function destroyCriteria(
    EvaluationFactor $evaluation_factor,
    EvaluationCriterion $criterion
) {
    $criterion->delete();

    return redirect()
        ->route(
            'evaluation-factors.criteria',
            $evaluation_factor
        )
        ->with('success', 'Evaluation criterion deleted successfully.');
}
}