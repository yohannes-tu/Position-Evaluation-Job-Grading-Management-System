<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionController extends Controller
{
    /**
     * Display a listing of positions.
     */
    public function index(): View
    {
        $positions = Position::with('department')
            ->latest()
            ->paginate(10);

        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new position.
     */
    public function create(): View
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('positions.create', compact('departments'));
    }

    /**
     * Store a newly created position.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/',
                'unique:positions,code',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'responsibilities' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'education' => [
                'nullable',
                'string',
                'max:255',
            ],

            'experience' => [
                'nullable',
                'string',
                'max:255',
            ],

            'employment_type' => [
                'required',
                'in:full_time,part_time,contract',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'department_id.required' => 'Please select a department.',

            'department_id.exists' => 'The selected department is invalid.',

            'title.required' => 'Please enter the position title.',

            'code.required' => 'Please enter a position code.',

            'code.unique' => 'This position code is already being used.',

            'code.regex' => 'The position code may only contain letters, numbers, hyphens, and underscores.',

            'employment_type.required' => 'Please select an employment type.',

            'employment_type.in' => 'Please select a valid employment type.',
        ]);

        Position::create($validated);

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified position.
     */
    public function show(Position $position): View
    {
        $position->load('department');

        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified position.
     */
    public function edit(Position $position): View
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('positions.edit', compact(
            'position',
            'departments'
        ));
    }

    /**
     * Update the specified position.
     */
    public function update(
        Request $request,
        Position $position
    ): RedirectResponse {

        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/',
                'unique:positions,code,' . $position->id,
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'responsibilities' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'education' => [
                'nullable',
                'string',
                'max:255',
            ],

            'experience' => [
                'nullable',
                'string',
                'max:255',
            ],

            'employment_type' => [
                'required',
                'in:full_time,part_time,contract',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $position->update($validated);

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position updated successfully.');
    }
   public function deactivate(Position $position)
{
    $position->update([
        'is_active' => false,
    ]);

    return redirect()
        ->route('positions.index')
        ->with('success', 'Position deactivated successfully.');
}
   public function activate(Position $position)
{
    $position->update([
        'is_active' => true,
    ]);

    return redirect()
        ->route('positions.index')
        ->with('success', 'Position activated successfully.');
}
    /**
     * Remove the specified position.
     */
    public function destroy(Position $position): RedirectResponse
    {
        $position->delete();

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    public function evaluate(Position $position)
{
    $factors = \App\Models\EvaluationFactor::with('criteria')
        ->orderBy('id')
        ->get();

    return view(
        'positions.evaluate',
        compact('position', 'factors')
    );
}
}
