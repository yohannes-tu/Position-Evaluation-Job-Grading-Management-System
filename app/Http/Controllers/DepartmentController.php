<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(): View
    {
        $departments = Department::latest()->paginate(10);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): View
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:departments,name',
        ],

        'code' => [
            'required',
            'string',
            'max:50',
            'regex:/^[A-Za-z0-9_-]+$/',
            'unique:departments,code',
        ],

        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'is_active' => [
            'required',
            'boolean',
        ],
    ], [
        'name.required' => 'Please enter the department name.',

        'name.unique' => 'A department with this name already exists.',

        'code.required' => 'Please enter a department code.',

        'code.unique' => 'This department code is already being used.',

        'code.regex' => 'The department code may only contain letters, numbers, hyphens, and underscores.',

        'description.max' => 'The description cannot exceed 1000 characters.',

        'is_active.required' => 'Please select the department status.',
    ]);

    Department::create($validated);

    return redirect()
        ->route('departments.index')
        ->with('success', 'Department created successfully.');
}

    /**
     * Display the specified department.
     */
    public function show(Department $department): View
    {
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(
    Request $request,
    Department $department
): RedirectResponse {

    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:departments,name,' . $department->id,
        ],

        'code' => [
            'required',
            'string',
            'max:50',
            'regex:/^[A-Za-z0-9_-]+$/',
            'unique:departments,code,' . $department->id,
        ],

        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'is_active' => [
            'required',
            'boolean',
        ],
    ], [
        'name.required' => 'Please enter the department name.',

        'name.unique' => 'A department with this name already exists.',

        'code.required' => 'Please enter a department code.',

        'code.unique' => 'This department code is already being used.',

        'code.regex' => 'The department code may only contain letters, numbers, hyphens, and underscores.',

        'description.max' => 'The description cannot exceed 1000 characters.',

        'is_active.required' => 'Please select the department status.',
    ]);

    $department->update($validated);

    return redirect()
        ->route('departments.index')
        ->with('success', 'Department updated successfully.');
}

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department): RedirectResponse
{
    $department->delete();

    return redirect()
        ->route('departments.index')
        ->with('success', 'Department deleted successfully.');
}
}