<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\SalaryScale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryScaleController extends Controller
{
    /**
     * Display all salary scales.
     */
    public function index(): View
    {
        $salaryScales = SalaryScale::with('grade')
            ->latest('effective_date')
            ->paginate(10);

        return view(
            'salary-scales.index',
            compact('salaryScales')
        );
    }

    /**
     * Show the form for creating a salary scale.
     */
    public function create(): View
{
    $grades = \App\Models\Grade::where('is_active', true)
        ->orderBy('min_score')
        ->get();

    return view('salary-scales.create', compact('grades'));
}

    /**
     * Store a new salary scale.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade_id' => [
                'required',
                'exists:grades,id',
            ],

            'minimum_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'midpoint_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'effective_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
         * Salary validation:
         *
         * Minimum <= Midpoint <= Maximum
         */
        if (
            $validated['minimum_salary']
            > $validated['midpoint_salary']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'midpoint_salary' =>
                        'Midpoint salary cannot be lower than minimum salary.',
                ]);
        }

        if (
            $validated['midpoint_salary']
            > $validated['maximum_salary']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'maximum_salary' =>
                        'Maximum salary cannot be lower than midpoint salary.',
                ]);
        }

        SalaryScale::create($validated);

        return redirect()
            ->route('salary-scales.index')
            ->with(
                'success',
                'Salary scale created successfully.'
            );
    }

    /**
     * Display a salary scale.
     */
    public function show(SalaryScale $salaryScale): View
    {
        $salaryScale->load('grade');

        return view(
            'salary-scales.show',
            compact('salaryScale')
        );
    }

    /**
     * Show the edit form.
     */
    public function edit(SalaryScale $salaryScale): View
{
    $grades = Grade::where('is_active', true)
        ->orderBy('min_score')
        ->get();

    return view('salary-scales.edit', compact(
        'salaryScale',
        'grades'
    ));
}

    /**
     * Update a salary scale.
     */
    public function update(
        Request $request,
        SalaryScale $salaryScale
    ): RedirectResponse {

        $validated = $request->validate([
            'grade_id' => [
                'required',
                'exists:grades,id',
            ],

            'minimum_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'midpoint_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'effective_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
         * Validate salary order.
         */
        if (
            $validated['minimum_salary']
            > $validated['midpoint_salary']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'midpoint_salary' =>
                        'Midpoint salary cannot be lower than minimum salary.',
                ]);
        }

        if (
            $validated['midpoint_salary']
            > $validated['maximum_salary']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'maximum_salary' =>
                        'Maximum salary cannot be lower than midpoint salary.',
                ]);
        }

        $salaryScale->update($validated);

        return redirect()
            ->route('salary-scales.index')
            ->with(
                'success',
                'Salary scale updated successfully.'
            );
    }

    /**
     * Delete a salary scale.
     */
    public function destroy(
        SalaryScale $salaryScale
    ): RedirectResponse {

        $salaryScale->delete();

        return redirect()
            ->route('salary-scales.index')
            ->with(
                'success',
                'Salary scale deleted successfully.'
            );
    }

    /**
     * Activate a salary scale.
     */
    public function activate(
        SalaryScale $salaryScale
    ): RedirectResponse {

        $salaryScale->update([
            'status' => 'active',
        ]);

        return back()->with(
            'success',
            'Salary scale activated successfully.'
        );
    }

    /**
     * Deactivate a salary scale.
     */
    public function deactivate(
        SalaryScale $salaryScale
    ): RedirectResponse {

        $salaryScale->update([
            'status' => 'inactive',
        ]);

        return back()->with(
            'success',
            'Salary scale deactivated successfully.'
        );
    }
}